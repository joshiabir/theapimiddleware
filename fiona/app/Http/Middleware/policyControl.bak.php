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

class policyControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $time_f = microtime(true);
        //creating log object for the first time with setting the key
        $log = (object) [
            'key' => $time_f,
            'url' => request()->fullUrl(),
            'client' => json_encode($request->ips(), JSON_PRETTY_PRINT),
            'host' => request()->getHost(),
            'request_headers' => json_encode($request->headers->all(), JSON_PRETTY_PRINT),
            'request_body' => json_encode($request->getContent(), JSON_PRETTY_PRINT),
            'request_method' => $request->method(),
            'response_headers' => 'null',
            'response_body' => 'null',
            'response_status' => '403',
            'response_time' => 'null',
            'domain_resolved' => 'null',
            'analysis' => 'Blocked',
            'prams' => request()->all(),
        ];

        $time_2 = microtime(true);
       
       
       //Checking the first time if the database has the domain mapped
       
        $router = Router::where('host', '=', $request->getHost())->where('status', '=', 'Active')->get();
        $time_3 = microtime(true);
        if (count($router) > 0) {
            $log->domain_resolved = $router[0]['ip'];

            if ($router[0]['client_policy'] == 'black') {
                $allowed = client::where('ip', '=', $request->ips()[0])
                    ->where('host', '=', $request->getHost())->get();
                if (count($allowed) > 0) {
                    $log->response_headers = 'Blacklisted IP address';
                    $log->response_status = '403';

                    logProcessor::dispatch($log)->afterResponse();

                    return response()->json([
                        'message' => 'The method or API is forbidden, API Policy',
                    ], Response::HTTP_FORBIDDEN);
                }
            }
            if ($router[0]['client_policy'] == 'white') {
                $allowed = client::where('ip', '=', $request->ips()[0])
                    ->where('host', '=', $request->getHost())->get();
                if (!count($allowed) > 0) {
                    $log->response_headers = 'Blacklisted IP address';
                    $log->response_status = '403';

                    logProcessor::dispatch($log)->afterResponse();

                    return response()->json([
                        'message' => 'The method or API is forbidden, API Policy',
                    ], Response::HTTP_FORBIDDEN);
                }
            }

            if ($router[0]['policy'] == 'white') {
                $allowed = policy::where('ip', '=', $request->ips()[0])
                    ->where('host', '=', $request->getHost())->get();
                if (!count($allowed) > 0) {
                    $log->response_headers = 'Not Whitelisted IP address';
                    $log->response_status = '403';
                    logProcessor::dispatch($log)->afterResponse();

                    return response()->json([
                        'message' => 'The method or API is forbidden, API Policy',
                    ], Response::HTTP_FORBIDDEN);
                }
            }
            if ($router[0]['policy'] == 'black') {
                $blocked = policy::where('url', '=', $request->getRequestUri())
                    ->where('host', '=', $request->getHost())->get();
                if (count($blocked) > 0) {

                    $log->response_headers = 'Blocked by TAM Policy, API Policy';
                    $log->response_status = '403';
                    logProcessor::dispatch($log)->afterResponse();

                    return response()->json([
                        'message' => 'The method or API is forbidden',
                    ], Response::HTTP_FORBIDDEN);
                }
            }
            $protocol = Protocol::where('host', '=', $request->getHost())
                ->where('method', '=', $request->method())
                ->where('host', '=', $request->getHost())
                ->get();
            $time_4 = microtime(true);
            if (!count($protocol) > 0) {
                $log->response_headers = 'Blocked by TAM Policy, incorrect method';
                $log->response_status = '403';
                logProcessor::dispatch($log)->afterResponse();

                return response()->json([
                    'message' => 'The method or API is forbidden',
                ], Response::HTTP_FORBIDDEN);
            } else {
                $log->analysis = 'Passed';
                logProcessor::dispatch($log)->afterResponse();
                $time_5 = microtime(true);
                $input = $request->all();

                $input['logger_key'] = $time_f;
                $input['pcm_1'] = $time_f;
                $input['pcm_2'] = $time_2;
                $input['pcm_3'] = $time_3;
                $input['pcm_4'] = $time_4;
                $input['pcm_5'] = $time_5;
                $request->replace($input);

                return $next($request);
            }
        } else {

            //Failing from the first check if the domain is not in the database

            $log->domain_resolved = 'Failed';
            logProcessor::dispatch($log)->afterResponse();

            return response()->json([
                'message' => 'Host ' . $request->getHost() . ' not found',
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
