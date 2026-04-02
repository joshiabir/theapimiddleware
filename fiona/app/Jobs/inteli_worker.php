<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Helpers\JsonHelper;
use App\Models\api_methods as method;
use App\Models\response_keys as ResKeys;
use App\Models\apis;
use App\Models\domain_routing;
use App\Models\request_keys as ReqKeys;
use App\Models\logger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\domain_expired;

class inteli_worker implements ShouldQueue
{
    use Queueable;

    protected $log;

    /**
     * Create a new job instance.
     */
    public function __construct($log)
    {
        $this->log = $log;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $expired = DB::select("select domain_routings.status as status,
        users.email as email, 
        domain_routings.host as host 
        from domain_routings,licenses,users 
        where users.email = licenses.email 
        and domain_routings.user_id = users.id 
        and domain_routings.status = 'Active'
        and licenses.end_date < ?
        and domain_routings.host = ?
        ", [Carbon::now()->timestamp, $this->log->host]);

        if (count($expired) > 0) {
            domain_routing::where('host', '=', $this->log->host)
                ->update(['status' => 'Inactive']);
            Mail::to($expired[0]->email)->send(new domain_expired(['domain' => $this->log->host]));
        }


        if ($this->log->analysis != 'Failed') {
            $host = $this->log->host;
            $url = parse_url($this->log->url);

            Apis::firstOrCreate(
                ['host' => $host, 'url' => $url['path']],
                ['api_id' => \Illuminate\Support\Str::uuid()]
            );

            // Adding methind to database and updating if already exists;

            $current = Method::where('host', '=', $this->log->host)
                ->where('url', '=', (parse_url($this->log->url, PHP_URL_PATH) ?? '/'))
                ->where('method', '=', $this->log->request_method)
                ->get();
            if (count($current) > 0) {
                Method::where('host', '=', $this->log->host)
                    ->where('url', '=', (parse_url($this->log->url, PHP_URL_PATH) ?? '/'))
                    ->where('method', '=', $this->log->request_method)
                    ->update(['count' => (int)$current[0]->count + 1]);
            } else {
                Method::create([
                    'host' => $this->log->host,
                    'url' => (parse_url($this->log->url, PHP_URL_PATH) ?? '/'),
                    'method' => $this->log->request_method,
                    'count' => '1',
                ]);
            }


            // Adding keys and strucutre to the database for requests and response

            if ($this->log->response_status == 200 && json_validate($this->log->response_body) === TRUE) {
                $reskeys = ResKeys::where('host', '=', $this->log->host)
                    ->where('url', '=', (parse_url($this->log->url, PHP_URL_PATH) ?? '/'))
                    ->where('method', '=', $this->log->request_method)
                    ->get();

                if (count($reskeys) > 0) {
                    //
                } else {

                    $keys = [];
                    if (json_validate($this->log->response_body)) {

                        $keys = JsonHelper::replaceValuesWithTypes(json_decode($this->log->response_body));
                        $flattened = JsonHelper::flattenJsonKeys($keys);

                        $keys =  json_encode($flattened, JSON_PRETTY_PRINT);
                    }
                    ResKeys::create([
                        'host' => $this->log->host,
                        'url' => (parse_url($this->log->url, PHP_URL_PATH) ?? '/'),
                        'method' => $this->log->request_method,
                        'keys' => $keys,
                    ]);
                }
            }

            $prams = logger::select('prams')->where('key', '=', $this->log->logger_key)->get();

            if ($prams != '') {
                $reqkeys = ReqKeys::where('host', '=', $this->log->host)
                    ->where('url', '=', (parse_url($this->log->url, PHP_URL_PATH) ?? '/'))
                    ->where('method', '=', $this->log->request_method)
                    ->get();

                if (count($reqkeys) > 0) {
                    //
                } else {

                    $keys = [];
                    if (json_validate($prams[0]->prams)) {

                        $keys = JsonHelper::replaceValuesWithTypes(json_decode($prams[0]->prams));
                        $flattened = JsonHelper::flattenJsonKeys($keys);

                        $keys =  json_encode($flattened, JSON_PRETTY_PRINT);
                    }
                    ReqKeys::create([
                        'host' => $this->log->host,
                        'url' => (parse_url($this->log->url, PHP_URL_PATH) ?? '/'),
                        'method' => $this->log->request_method,
                        'keys' => $keys,
                    ]);
                }
            }
        }
    }
}
