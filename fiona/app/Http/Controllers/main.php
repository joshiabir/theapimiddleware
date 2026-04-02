<?php

namespace App\Http\Controllers;

use App\Jobs\logProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\domain_routing as Dnr;
use App\Models\logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\RequestException;
use App\Services\dlp_service;

class main extends Controller
{
    public function handleRequest(Request $request)
    {   

        $test = FALSE;

        $mc_1 = microtime(true);

        $host = request()->getHost();
        $failed = FALSE;
        $failed_response = [];
        $dlp_request = app(dlp_service::class)->dlp($request->getContent(), $host, $request->getRequestUri());
        if ($dlp_request === 'Blocked') {
            return response()->json(['error' => 'Request blocked by DLP policy.'], 403);
        }
        $request->clean_body = $dlp_request->body;

        $domain_resolved = Dnr::where('host', '=', $host)->get();
        if (!isset($domain_resolved[0])) {
            return response('Oops! Route not found', 404);
        }
        $protocol = $domain_resolved[0]['protocol'];
        $domain_resolved = $domain_resolved[0]['ip'];


        $request->headers->add(['host' => $domain_resolved]);

        // Get all headers from the incoming request
        $headers = $request->headers->all();
        $mc_2 = microtime(true);
        // Ensure the Accept header includes 'text/html' and 'application/json'
        if (!isset($headers['accept'])) {
            $headers['accept'] = ['text/html, application/json'];
        } else {
            $currentAccept = implode(', ', $headers['accept']);
            if (!str_contains($currentAccept, 'text/html')) {
                $currentAccept .= ', text/html';
            }
            if (!str_contains($currentAccept, 'application/json')) {
                $currentAccept .= ', application/json';
            }
            $headers['accept'] = [$currentAccept];
        }
        $mc_3 = microtime(true);
        try {

            $response = Http::withHeaders($request->headers->all())
                ->withOptions([
                    'verify' => false,
                    'Accept-Encoding' => '',
                    'Connection' => 'keep-alive',
                    'Keep-Alive' => 'timeout=5, max=100'
                ])
                ->send($request->method(), "{$protocol}://{$domain_resolved}{$request->getRequestUri()}", [
                    'body' => $request->clean_body,
                ]);
            $mc_4 = microtime(true);
        } catch (RequestException $e) {
            // Log the error fo r debugging
            $mc_4 = microtime(true);
            $failed = TRUE;
            // Return a user-friendly error message
            $failed_response = [
                'error' => 'There was an issue processing your request.',
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            // Catch any other exceptions
            $mc_4 = microtime(true);
            $failed = TRUE;
            $failed_response = [
                'error' => 'An unexpected error occurred.',
                'message' => $e->getMessage(),
            ];
        }
        $mc_5 = microtime(true);
        $time = ($mc_5 - $mc_3);
        $body = app(dlp_service::class)->dlp($response->body(), $host, $request->getRequestUri());
        if ($body == 'Blocked') {
            // Return a user-friendly error message
            $failed = TRUE;
            $failed_response = [
                'error' => 'There was an issue processing your request.',
                'message' => 'The response is blocked',
            ];

        }

        if ($failed === FALSE && $response->status() > 299) {
            $failed = TRUE;

            $cleanedBody = $body;


            $failed_response = [
                'status' => $response->status(),
                'error' => 'An unexpected error occurred.',
                'message' => $cleanedBody,
            ];
        }

        if (!$failed) {
            $headers = $response->headers();
            $simplified = [];
            foreach ($headers as $key => $value) {
                // Take the first element of each nested array
                $simplified[$key] = is_array($value) ? $value[0] : $value;
            }


            $cleanedBody = $body->body;

            if ($body->count > 0) {
                $analysis = 'DLP';
                $code = 'DLP';
            } else {
                $analysis = 'PASS';
                $code = 'PASS';
            }

            $log = (object) [
                'logger_key' => $request->logger_key,
                'host' => $host,
                'url' => $request->getRequestUri(),
                'response_status' => $response->status(),
                'response_headers' => json_encode($simplified, JSON_PRETTY_PRINT),
                'response_body' => $cleanedBody,
                'response_time' => $time,
                'dlp' => $body->replacements,
                'dlp_count' => $body->count,
                'analysis' => $analysis,
                'request_method' => $request->method(),
                'request_body' => $request->clean_body,
                'middleware_response' => (microtime(true) - $request->pcm_1) - ($mc_4 - $mc_3),
            ];
            $mc_6 = microtime(true);

            logProcessor::dispatch($log);
            $mc_7 = microtime(true);
            unset($simplified['Transfer-Encoding']);  // Remove Transfer-Encoding
            unset($simplified['transfer-encoding']);  // Remove Transfer-Encoding
            if ($test === TRUE) {

                return response()->json([

                    'pc-2' => $request->pcm_2 - $request->pcm_1,
                    'pc-3' => $request->pcm_3 - $request->pcm_2,
                    'pc-4' => $request->pcm_4 - $request->pcm_3,
                    'pc-5' => $request->pcm_5 - $request->pcm_4,
                    'mc_0' => $mc_1 - $request->pcm_5,
                    'mc_1' => $mc_2 - $mc_1,
                    'mc_2' => $mc_3 - $mc_2,
                    'mc_3 HTTP' => $mc_4 - $mc_3,
                    'mc_4' => $mc_5 - $mc_4,
                    'mc_5' => $mc_6 - $mc_5,
                    'mc_6' => $mc_7 - $mc_6,
                    'mc_7' => microtime(true) - $mc_7,
                    'total' => (microtime(true) - $request->pcm_1) - ($mc_4 - $mc_3),
                    'dlp' => $body->replacements,
                    'dlp_count' => $body->count,
                    // 'headers' => $simplified,
                    //'prams'=>$clean_body,
                ], 200);
            } else {
                $simplified['Content-Length'] = strlen($cleanedBody);
                return response($cleanedBody, $response->status())->withHeaders($simplified);
            }
        } else {
            $log = (object) [
                'logger_key' => $request->logger_key,
                'host' => $host,
                'url' => $request->getRequestUri(),
                'response_status' => isset($response) && method_exists($response, 'status') ? $response->status() : 500,
                'response_headers' => $failed_response,
                'response_body' => NULL,
                'response_time' => $time,
                'analysis' => 'REQF',
                'request_method' => $request->method(),
                'dlp' => $body->replacements ?? '',
                'dlp_count' => $body->count ?? '',
                'middleware_response' => (microtime(true) - $request->pcm_1) - ($mc_4 - $mc_3)
            ];

            logProcessor::dispatch($log);
            $status = isset($response) && method_exists($response, 'status') ? $response->status() : 500;
            return response($failed_response, $status);
        }
    }

    
}
