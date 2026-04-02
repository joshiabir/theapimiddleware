<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\policies as policy;
use App\Models\domain_routing as Router;
use App\Models\protocol;
use App\Jobs\logProcessor;
use App\Models\client;
use Illuminate\Support\Facades\DB;
use App\Services\dlp_service;

class policyControl
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function fail_with_grace($log, $reason, $dump, $code)
    {
        $log->request_headers = json_encode($dump->headers->all(), JSON_PRETTY_PRINT);
        $log->request_body = json_encode($dump->getContent(), JSON_PRETTY_PRINT);
        $log->analysis = $code;
        logProcessor::dispatch($log);
        return response()->json([
            'Error' => $reason,
        ], Response::HTTP_FORBIDDEN);
    }
    public function handle(Request $request, Closure $next): Response
    {
        $time_f = microtime(true);
        //creating log object for the first time with setting the key
        $log = (object) [
            'key' => $time_f,
            'url' => request()->fullUrl(),
            'client' => json_encode($request->ips(), JSON_PRETTY_PRINT),
            'host' => request()->getHost(),
            'request_method' => $request->method(),
            'response_headers' => 'null',
            'response_body' => 'null',
            'response_status' => '403',
            'response_time' => 'null',
            'domain_resolved' => 'null',
            'analysis' => 'Pending',
            'prams' => app(dlp_service::class)->dlp(json_encode($request->all(), JSON_PRETTY_PRINT), request()->getHost(), $request->getRequestUri())->body,
        ];

        $time_2 = microtime(true);


        //Checking the first time if the database has the domain mapped

        $router = Router::where('host', '=', $request->getHost())->where('status', '=', 'Active')->get();
        $time_3 = microtime(true);
        //Setting default settings 
        if (count($router) == 0) {
            return $this->fail_with_grace($log, "Domain not found or decativated", $request, 'DNF');
        }

        // Domain-level defaults — kept separately, never overwritten by cluster values
        $domain_user_rpm   = (int) $router[0]->rate_limit;
        $domain_global_rpm = (int) $router[0]->rate_limit * 10;

        $required_auth = 0;
        $encryption    = 0;
        $honey_pots    = 0;
        $access_type   = 0;
        $users         = [];
        $status        = 0;
        $api_status    = 0;

        // Cluster-level limits — null means no cluster matched, skip cluster checks
        $cluster_id         = null;
        $cluster_user_rpm   = null;
        $cluster_global_rpm = null;

        //End of default settings

        $check_main = DB::select("select
                                apis.api_id as id,
                                clusters.id as cluster_id,
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
                                and clusters.host = ?", [parse_url($request->getRequestUri(), PHP_URL_PATH), $request->getHost()]);


        if (count($check_main) > 0 || $router[0]->policy == 'Discovery') {

            foreach ($check_main as $checks) {
                if ($checks->rule == 'required_auth')
                    $required_auth = $checks->value;
                if ($checks->rule == 'encryption')
                    $encryption = $checks->value;
                if ($checks->rule == 'global_rpm')
                    $cluster_global_rpm = (int) $checks->value;
                if ($checks->rule == 'honey_pots')
                    $honey_pots = $checks->value;
                if ($checks->rule == 'user_rpm')
                    $cluster_user_rpm = (int) $checks->value;
                if ($checks->rule == 'access_type')
                    $access_type = $checks->value;
                if ($checks->rule == 'users')
                    $users = json_decode($checks->value, true);
                $status     = $check_main[0]->status;
                $api_status = $check_main[0]->api_status;
                $cluster_id = $check_main[0]->cluster_id;
            }

            //checking if https is enabled
            if ($encryption == 'https') {
                if (!$request->isSecure()) {
                    return $this->fail_with_grace($log, "Please use HTTPs", $request, 'HTPSF');
                }
            }
            //Failing if cluster or API is turned off
            if ($status == 'Disabled' || $api_status == 'Disabled') {
                return $this->fail_with_grace($log, "Invalid URL or HOST", $request, 'BADACS');
            }
            //Failing if IP is not in white list
            if ($access_type == 'white' && !in_array($request->ip(), $users)) {
                return $this->fail_with_grace($log, "Forbidden Access", $request, 'IPWLF');
            }
            //Failing if the IP is in the black list
            if ($access_type == 'black' && in_array($request->ip(), $users)) {
                return $this->fail_with_grace($log, "Forbidden Access", $request, 'IPBLF');
            }
            //checking if the request has authentication headers or prams
            if ($required_auth == 1) {
                $auth_h = $auth_p = '';
                $auth_h = $request->header('authorization');
                $auth_p = $request->query('authorization') ?? $request->input('authorization');
                if ($auth_p == '' && $auth_h == '') {
                    return $this->fail_with_grace($log, "Forbidden Access - Requires Authentication", $request, 'AUTHVF');
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //                       Need to add Protocols                          //
            //////////////////////////////////////////////////////////////////////////


            $log->analysis = 'PASS';
            $log->request_headers = json_encode($request->headers->all(), JSON_PRETTY_PRINT);
            $log->request_body = app(dlp_service::class)->dlp(json_encode($request->getContent(), JSON_PRETTY_PRINT), request()->getHost(), $request->getRequestUri())->body;
           
            logProcessor::dispatch($log, 'PASS');
        
            $time_4 = microtime(true);
            $input = $request->all();
            $input['logger_key'] = $time_f;
            $input['pcm_1'] = $time_f;
            $input['pcm_2'] = $time_2;
            $input['pcm_3'] = $time_3;
            $input['pcm_4'] = $time_4;
            $input['domain_user_rpm']   = $domain_user_rpm;
            $input['domain_global_rpm'] = $domain_global_rpm;
            $input['cluster_id']        = $cluster_id;
            $input['cluster_user_rpm']  = $cluster_user_rpm;
            $input['cluster_global_rpm']= $cluster_global_rpm;
            $request->replace($input);


            return $next($request);
        }

        return response()->json([
            'message' => 'Invalid Request URL',
        ], Response::HTTP_FORBIDDEN);
    }
}
