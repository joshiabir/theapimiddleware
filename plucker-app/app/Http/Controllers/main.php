<?php

namespace App\Http\Controllers;

use App\Mail\new_register;
use App\Models\apis;
use App\Models\blog;
use App\Models\cluster;
use App\Models\cluster_api;
use App\Models\cluster_policy;
use App\Models\domain_routing as Router;
use App\Models\domain_routing;
use App\Models\dlp_log;
use App\Models\incidents;
use App\Models\license;
use App\Models\logger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class main extends Controller
{
    function public(Request $request)
    {
        return view('welcome');
    }
    function register(Request $request)
    {
        if (isset($request->email) && isset($request->name))
            DB::select("insert into register ('email','name','timestamp') values(?,?,?)", [$request->email, $request->name, time()]);
        Mail::to('joshi@theapimiddleware.com')->send(new new_register(['email' => $request->email, 'name' => $request->name]));
        $this->slack_hook("new user $request->email with $request->name.");
        return view('welcomeback', ['register' => TRUE]);
    }
    function slack_hook($message)
    {
        // Slack webhook URL should be set in .env as SLACK_WEBHOOK_URL
    }
    function dashboard(Request $request)
    {
        $this->slack_hook("User " . Auth::user()->email . " in Dashboard by IP - " . implode(',', $request->ips()) . " and " . $request->header('User-Agent'));
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        $domain = '';
        if (isset($request->domain))
            $domain = base64_decode($request->domain);

        if ($domain == '' && count($domains) > 0) {
            $domain = $domains[0]->host;
        }
        if (count($domains) < !0) {
            return redirect('init');
        } else {

            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d');
                $end_time = date('Y-m-d');

                return redirect('/dashboard' . '/' . base64_encode($domain) . '/?start_datetime=' . $start_time . '&end_datetime=' . $end_time);
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }


            //echo $start_time;
            return view('dashboard', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain]);
        }
    }
    function logs(Request $request)
    {

        $key = '';
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        $domain = '';
        if (isset($request->domain))
            $domain = base64_decode($request->domain);
        if ($domain == '') {
            $domain = $domains[0]->host;
        }
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {

            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-12 hours'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            if (isset($request->key)) {
                $key = $request->key;
            }
            //echo $start_time;
            return view('logs', ['start_time' => $start_time, 'end_time' => $end_time, 'key' => $key, 'domain' => $domain]);
        }
    }
    function config(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        $domain = '';
        if (isset($request->domain))
            $domain = base64_decode($request->domain);
        if ($domain == '') {
            $domain = $domains[0]->host;
        }
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-12 hours'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            if (isset($request->key)) {
                $key = $request->key;
            }
            return view('config', ['domain' => $domain, 'start_time' => $start_time, 'end_time' => $end_time]);
        }
    }
    function settings(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        $domain = '';
        if (isset($request->domain))
            $domain = base64_decode($request->domain);
        if ($domain == '') {
            $domain = $domains[0]->host;
        }
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {

            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-12 hours'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            if (isset($request->key)) {
                $key = $request->key;
            }
            return view('settings', ['domain' => $domain, 'domains' => $domains, 'start_time' => $start_time, 'end_time' => $end_time]);
        }
    }
    function apis(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $api = '';
            if (isset($request->api))
                $api = base64_decode($request->api);
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }
            return view('apis.view', ['start_time' => $start_time, 'end_time' => $end_time, 'api' => $api, 'domain' => $domain]);
        }
    }
    function clusters(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $api = '';
            if (isset($request->api))
                $api = base64_decode($request->api);
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }
            return view('apis.cluster.logs', ['start_time' => $start_time, 'end_time' => $end_time, 'api' => $api, 'domain' => $domain, 'cluster_id' => $request->cluster_id, 'cluster_name' => $request->cluster_name]);
        }
    }
    function securitylogs(Request $request)
    {

        $key = '';
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {

            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-12 hours'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            if (isset($request->key)) {
                $key = $request->key;
            }
            //echo $start_time;
            return view('securitylogs', ['start_time' => $start_time, 'end_time' => $end_time, 'key' => $key]);
        }
    }

    function add_domains_get(Request $request)
    {

        $domains = Router::where('user_id', '=', Auth::user()->id)->get();

        return view('domain_management');
    }

    function init(Request $request)
    {
        // Ensure a trial license exists for this user (firstOrCreate avoids redirect loop)
        $license = license::firstOrCreate(
            ['email' => Auth::user()->email],
            [
                'domains'  => '2',
                'end_date' => \Carbon\Carbon::now()->addDays(15)->toDateTimeString(),
                'status'   => 'Trial',
                'rpm'      => '10',
            ]
        );

        // License validity checks
        if (strtotime($license->end_date) < time()) {
            return view('domain_management', ['error' => 'date_expired', 'step' => '', 'domain' => '']);
        }

        $domainCount = domain_routing::where('user_id', Auth::user()->id)->count();
        if ((int) $license->domains <= $domainCount) {
            return view('domain_management', ['error' => 'domain_expired', 'step' => '', 'domain' => '']);
        }

        $step   = $request->query('step', '1');
        $domain = $request->query('domain', '');
        $error  = '';

        // Step 1 POST: create domain routing
        if ($request->isMethod('post') && $step == '1') {
            $domain = trim($request->input('domain_a', ''));

            if ($domain === '' || $request->input('ip', '') === '') {
                return view('domain_management', ['error' => 'Domain and backend IP are required.', 'step' => '1', 'domain' => '']);
            }

            if (Router::where('host', $domain)->exists()) {
                return view('domain_management', ['error' => "The domain {$domain} is already registered.", 'step' => '1', 'domain' => '']);
            }

            Router::create([
                'host'          => $domain,
                'ip'            => $request->input('ip'),
                'user_id'       => Auth::user()->id,
                'policy'        => 'Discovery',
                'status'        => 'Inactive',
                'rate_limit'    => '60',
                'protocol'      => $request->input('protocol', 'https'),
                'client_policy' => 'Default',
            ]);

            return redirect()->route('init', ['step' => '2', 'domain' => $domain]);
        }

        // Step 3 GET: activate domain
        if ($step == '3' && $domain !== '') {
            Router::where('host', $domain)
                ->where('user_id', Auth::user()->id)
                ->update(['status' => 'Active']);

            return view('domain_management', ['error' => '', 'step' => '3', 'domain' => $domain]);
        }

        return view('domain_management', ['error' => $error, 'step' => $step, 'domain' => $domain]);
    }
    function logger(Request $request)
    {
        $this->slack_hook("User " . $request->email . " in $request->url with " . implode(',', $request->ips()));
    }
    function add_domain(Request $request)
    {
        $domain = $request->domain_a ?: $request->domain_b;

        Router::create([
            'host'          => $domain,
            'ip'            => $request->ip,
            'user_id'       => Auth::user()->id,
            'policy'        => 'Discovery',
            'status'        => 'Inactive',
            'rate_limit'    => '60',
            'protocol'      => $request->input('protocol', 'https'),
            'client_policy' => 'Default',
        ]);

        return redirect()->route('config', ['domain' => base64_encode($domain)]);
    }

    function auth_callback(Request $request)
    {
        if (!in_array($request->driver, ['google', 'microsoft', 'github'], TRUE)) {
            return response(403);
        }
        $user = Socialite::driver($request->driver)->user();

        $user = User::updateOrCreate([
            'auth_id' => $user->id,
        ], [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'none',
            'auth_token' => $user->token,
            'auth_refresh_token' => $user->refreshToken ?? 0,
        ]);
        $user->markEmailAsVerified();
        Auth::login($user);
        return redirect('/dashboard');
    }

    function blog(Request $request)
    {
        $links = blog::select('title', 'slug', 'img', 'sub_heading', 'created_at')->orderby('created_at', 'desc')->get();
        $blog = blog::where('slug', '=', $request->slug)->get();
        $heading = blog::select('headings')->where('slug', '=', $request->slug);
        if ($request->slug == '') {
            $blog = (object) 
                [
                    'title' => 'Blogs from the API Middleware',
                    'sub_heading' => 'Learn more about APIs with out blogs at the API Middleware',
                    'slug' => '',
                    'img' => '',
                ];
            return view('blog', ['blog' => $blog, 'links' => $links, 'slug' => 'home']);
        } else {
            foreach ($blog as $b) {

                return view('blog', ['links' => $links, 'blog' => $b, 'headings' => $heading, 'slug' => $request->slug]);
            }
        }
    }

    function api_cluster(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $api = '';
            if (isset($request->api))
                $api = base64_decode($request->api);
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            $clusters = cluster::select('id', 'name', 'status', 'owner', 'policy_id')->where('host', '=', $domain)->get();
            if (!count($clusters) > 0) {
                $cluster = cluster::create([
                    'name' => 'Default',
                    'description' => 'This is the default Cluster with all APIs, you can remove and add APIs to different Clusters',
                    'policy_id' => 'None',
                    'status' => 'Active',
                    'owner' => Auth::user()->id,
                    'host' => $domain,
                ]);
                $clusters = [$cluster];
                $apis = apis::select('api_id')->where('host', '=', $domain)->get();
                foreach ($apis as $api) {
                    cluster_api::create([
                        'cluster_id' => $cluster->id,
                        'api_id' => $api->api_id,
                        'status' => 'Active',
                    ]);
                }
                //
            }

            return view('apis.cluster.view', ['start_time' => $start_time, 'end_time' => $end_time, 'api' => $api, 'domain' => $domain, 'clusters' => $clusters]);
        }
    }
    function api_cluster_policy(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $api = '';
            if (isset($request->api))
                $api = base64_decode($request->api);
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            $cluster_policy = cluster_policy::all()->where('cluster', '=', $request->cluster)->where('host', '=', $domain);
            if (!count($cluster_policy) > 0) {
                $first_policy = cluster_policy::create([
                    'name' => 'Default',
                    'owner' => Auth::user()->id,
                    'host' => $domain,
                    'description' => 'This is your first policy.',
                    'status' => 'Active',
                ]);
                $cluster_policy = [$first_policy];
            }
            return view('apis.cluster.policy', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain, 'cluster_policy' => $cluster_policy]);
        }
    }
    function api_cluster_edit(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }


            $free_apis_old = DB::select('select * from apis where host = ? and api_id IN (select api_id from cluster_apis where cluster_id = "Free")', [$domain]);
            $free_apis_new = DB::select('select * from apis where host = ? and api_id NOT IN (select api_id from cluster_apis)', [$domain]);

            if ($request->cluster == 'new') {
                return view('apis.cluster.edit', ['start_time' => $start_time, 'end_time' => $end_time, 'apis' => [], 'domain' => $domain, 'cluster' => ['0' => (object) ['name' => 'My New Cluster', 'policy_id' => 'new', 'description' => '', 'id' => 'new']], 'free_apis_new' => $free_apis_new, 'free_apis_old' => $free_apis_old]);
            }
            $cluster = cluster::select('id', 'name', 'status', 'owner', 'policy_id')
                ->where('id', '=', $request->cluster)
                ->where('owner', '=', Auth::user()->id)
                ->get();

            $apis = Apis::leftJoin('cluster_apis', 'cluster_apis.api_id', '=', 'apis.api_id')
                ->leftJoin('loggers', function ($join) use ($start_time, $end_time) {
                    $join->on('loggers.url', '=', 'apis.url')
                        ->where('loggers.host', '=', 'localhost')
                        ->whereBetween('loggers.created_at', [$start_time, $end_time]);
                })
                ->where('cluster_apis.cluster_id', $request->cluster)
                ->groupBy('apis.api_id', 'apis.url')
                ->select([
                    DB::raw('ANY_VALUE(cluster_apis.status) as status'), // ✅ Fix here
                    'apis.api_id as api_id',
                    'apis.url',
                    DB::raw('COUNT(DISTINCT loggers.client) as clients'),
                    DB::raw('COUNT(loggers.id) as hits'),
                    DB::raw('SUM(CASE WHEN loggers.response_status != 404 THEN 1 ELSE 0 END) as failed')
                ])
                ->get();


            //I hope we are checking authentication on the cluster before proceeding here
            // dd(print_r($free_apis_old));
            return view('apis.cluster.edit', ['start_time' => $start_time, 'end_time' => $end_time, 'apis' => $apis, 'domain' => $domain, 'cluster' => $cluster, 'free_apis_new' => $free_apis_new, 'free_apis_old' => $free_apis_old]);
        }
    }
    function api_cluster_policy_edit(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            $policy_id = $request->policy;
            //I hope we are checking authentication on the cluster before proceeding here
            return view('apis.cluster.policy_edit', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain, 'policy_id' => $policy_id]);
        }
    }
    function incidents(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            return view('security.incidents', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain]);
        }
    }
    function investigate(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            //Get all incidents for LOG Key
            $dlp = null;
            if ($request->type == 'key') {
                $key = base64_decode($request->key);
                $incidents = incidents::where('log_key', $key)->get();
                $log = logger::where('key', $key)->get();
                $dlp = dlp_log::where('log_id', $key)->first();
            } elseif ($request->type == 'api') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and loggers.url = ?", [base64_decode($request->key)]);
                $log = [];
            } elseif ($request->type == 'ip') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and loggers.client = ?", [base64_decode($request->key)]);
                $log = [];
            } elseif ($request->type == 'inc') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and incidents.type = ?", [base64_decode($request->key)]);
                $log = [];
            } else {
                $type = 'NA';
            }

            return view('security.investigate', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain, 'log_id' => $request->log_id, 'incidents' => $incidents, 'log' => $log, 'type' => $request->type, 'dlp' => $dlp]);
        }
    }
     function log_investigate(Request $request)
    {
        $domains = Router::where('user_id', '=', Auth::user()->id)->get();
        if (count($domains) < !0) {
            return redirect('add_domains_get');
        } else {
            if (!isset($request->start_datetime) || !isset($request->end_datetime)) {
                $start_time = date('Y-m-d', strtotime('-1 day'));
                $end_time = date('Y-m-d');
            } else {
                $start_time = $request->start_datetime;
                $end_time = $request->end_datetime;
            }
            $domain = '';
            if (isset($request->domain))
                $domain = base64_decode($request->domain);
            if ($domain == '') {
                $domain = $domains[0]->host;
            }

            //Get all incidents for LOG Key
            $dlp = null;
            if ($request->type == 'key') {
                $key = base64_decode($request->key);
                $incidents = incidents::where('log_key', '=', $key)->get();
                $log = logger::where('key', '=', $key)->get();
                $dlp = dlp_log::where('log_id', $key)->first();
            } elseif ($request->type == 'api') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and loggers.url = ?", [base64_decode($request->key)]);
                $log = [];
            } elseif ($request->type == 'ip') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and loggers.client = ?", [base64_decode($request->key)]);
                $log = [];
            } elseif ($request->type == 'inc') {
                $incidents = DB::select("select loggers.*,incidents.* from loggers,incidents where loggers.key = incidents.log_key and incidents.type = ?", [base64_decode($request->key)]);
                $log = [];
            } else {
                $type = 'NA';
            }

            return view('security.investigate', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain, 'log_id' => $request->log_id, 'incidents' => $incidents ?? '', 'log' => $log ?? [], 'type' => $request->type, 'dlp' => $dlp]);
        }
    }
    function test(Request $request)
    {
        // Mail::to('joshiabirdb@gmail.com')->send(new new_register(['name'=>'Akir','email'=>'oshi+ss@theapimiddleware.com']));

        // return dd('sent?');
        // $this->slack_hook("User $request->name- $request->email in Dashboard .");
    }
}
