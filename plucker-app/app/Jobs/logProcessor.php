<?php

namespace App\Jobs;

use App\Models\response_time;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\apis;
use App\Models\logger;
use App\Models\dlp_log;
use App\Jobs\inteli_worker;
use App\Jobs\incident_responder;
use App\Models\pii_list;
use App\Models\incidents;
use Illuminate\Support\Facades\Log;

class logProcessor implements ShouldQueue {
    use Queueable;

    protected $log;
    protected $code;

    public function __construct( $log, $code ) {
        $this->log = $log;
        $this->code = $code;
    }

    /**
    * Execute the job.
    */

    public function handle(): void {
        //Logging is off - 0

        //Auth Redaction is on - 1

        if ( !isset( $this->log->logger_key ) ) {
            //cleaning up storage processing
            $this->log = $this->clean_data( $this->log );

            logger::create( [
                'key' => $this->log->key,
                'url' => (parse_url( $this->log->url, PHP_URL_PATH ) ?? '/'),
                'client' => $this->log->client,
                'host' => $this->log->host,
                'request_headers' => $this->log->request_headers,
                'request_body' => $this->log->request_body,
                'request_method' => $this->log->request_method,
                'response_headers' => $this->log->response_headers,
                'response_body' => $this->log->response_body,
                'response_status' => $this->log->response_status,
                'response_time' => $this->log->response_time,
                'domain_resolved' => $this->log->domain_resolved,
                'analysis' => $this->log->analysis,
                'prams' => $this->log->prams,
            ] );
        }
        if ( isset( $this->log->logger_key ) ) {
            $this->log = $this->clean_data( $this->log );
            DB::table( 'loggers' )
            ->where( 'key', '=', $this->log->logger_key )
            ->update( [
                'response_status' => $this->log->response_status,
                'response_headers' => $this->log->response_headers,
                'response_body' => $this->log->response_body,
                'response_time' => $this->log->response_time,
                'middleware_response' => $this->log->middleware_response,
                'analysis' => $this->log->analysis,
            ] );
            dlp_log::create( [
                'log_id' => $this->log->logger_key,
                'host' => $this->log->host,
                'value' => json_encode( $this->log->dlp ),
                'count' => $this->log->dlp_count,
            ] );
            response_time::create( [
                'log_id' => $this->log->logger_key,
                'host' => $this->log->host,
                'url' => (parse_url( $this->log->url, PHP_URL_PATH ) ?? '/'),
                'response_time' => $this->log->response_time,
            ] );

            inteli_worker::dispatch( $this->log );
        }

        //print_r($this->code);
        if ( $this->log->analysis != 'PASS' ) {
            incidents::create( [
                'log_key'=>$this->log->logger_key ?? $this->log->key,
                'status'=>'created',
                'type'=>$this->log->analysis,
                'host'=>$this->log->host,
            ] );
        }
    }

    public function clean_data( $log ) {
        $check_main = DB::select( "select 
                                
        apis.api_id as id, 
        clusters.name as name, 
        clusters.status as status, 
        cluster_apis.status as api_status,
        apis.url as url,
        cluster_policy_lists.name as rule,
        cluster_policy_lists.value as value
    
         from 
        domain_routings,
        clusters,
        apis,
        cluster_apis,
        cluster_policies,
        cluster_policy_lists 
         where 	
    
        domain_routings.host = clusters.host 
        and apis.host = clusters.host
        and apis.url = ?
        and apis.api_id = cluster_apis.api_id
        and cluster_apis.cluster_id = clusters.id
        and clusters.policy_id = cluster_policies.id
        and cluster_policy_lists.policy_id = cluster_policies.id
        and clusters.host = ?", [ (parse_url( $log->url, PHP_URL_PATH ) ?? '/'), $log->host ] );

        if ( count( $check_main ) > 0 ) {
            //assiginig all values to policy_rules

            foreach ( $check_main as $checks ) {
                if ( $checks->rule == 'logging_http' )
                $logging_http = $checks->value;
                if ( $checks->rule == 'logging_gdpr' )
                $logging_gdpr = $checks->value;
                if ( $checks->rule == 'redact_auth' )
                $redact_auth = $checks->value;
                if ( $checks->rule == 'required_auth' )
                $required_auth = $checks->value;
                if ( $checks->rule == 'encryption' )
                $encryption = $checks->value;
                if ( $checks->rule == 'global_rpm' )
                $global_rpm = $checks->value;
                if ( $checks->rule == 'anomally_detection' )
                $anomaly_detection = $checks->value;
                if ( $checks->rule == 'honey_pots' )
                $honey_pots = $checks->value;
                if ( $checks->rule == 'pii_dlp' )
                $pii_dlp = $checks->value;
                if ( $checks->rule == 'user_rpm' )
                $user_rpm = $checks->value;
                if ( $checks->rule == 'access_type' )
                $access_type = $checks->value;
                if ( $checks->rule == 'users' )
                $users = json_decode( $checks->value, true );
            }

            //Obfuscating the request auth headers
            if ( isset( $log->request_headers ) ) {
                if ( $redact_auth == 1 ) {
                    $req_headers = json_decode( $log->request_headers );
                    if ( isset( $req_headers->cookie ) ) {
                        $req_headers->cookie = json_encode( [ 'Obfuscated' => md5( json_encode( $req_headers->cookie ) ) ] );
                    }
                    if ( isset( $req_headers->authorization ) ) {
                        $req_headers->authorization = json_encode( [ 'Obfuscated' => md5( json_encode( $req_headers->authorization ) ) ] );
                    }

                    $log->request_headers = json_encode( $req_headers );

                    $prams = json_decode( $log->prams );
                    if ( isset( $prams->authorization ) ) {
                        $prams->authorization = [ 'obfuscated' => md5( json_encode( $prams->authorization ) ) ];
                    }

                    $log->prams = json_encode( $prams );
                }
                //Obfuscating the PIIs in the header if GDPR is enabled
                if ( $logging_gdpr == 1 ) {

                    $pii_list = pii_list::all();
                    foreach ( $pii_list as $pii ) {
                        // if ( isset( $log->prams ) ) $log->prams = json_encode( [ 'test'=>'test' ] );
                        if ( isset( $log->prams ) ) {
                            $prams = preg_replace( $pii->regex, $pii->replacement, $log->prams );
                            $log->prams = $prams;
                        }
                    }
                }
            }
            //Removing all logs if the logging is disabled
            if ( $logging_http == 0 ) {
                $log->response_body = json_encode( [ 'Cluster Rule' => 'HTTP Loggging Disabled' ] );
                $log->request_headers = json_encode( [ 'Cluster Rule' => 'HTTP Loggging Disabled' ] );
                $log->request_body = json_encode( [ 'Cluster Rule' => 'HTTP Loggging Disabled' ] );
                $log->response_headers = json_encode( [ 'Cluster Rule' => 'HTTP Loggging Disabled' ] );
                $log->prams = json_encode( [ 'Cluster Rule' => 'HTTP Logging Disabled' ] );
            }
        }

        return $log;
    }

}
