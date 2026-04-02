<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use App\Jobs\logProcessor;

class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $ip   = $request->ip();

        $checks = [
            // 1. Server-wide absolute ceiling
            [
                'key'      => 'global',
                'limit'    => (int) env('GLOBAL_RATE_LIMIT', 1000),
                'analysis' => 'SRVRL',
                'message'  => 'Server is under load. Please try again later.',
            ],
            // 2. Domain-wide: all users on this domain combined
            [
                'key'      => 'domain:' . $host,
                'limit'    => $request->domain_global_rpm,
                'analysis' => 'DGRL',
                'message'  => 'Domain rate limit exceeded.',
            ],
            // 3. Domain: per individual user/IP
            [
                'key'      => 'domain:' . $host . ':' . $ip,
                'limit'    => $request->domain_user_rpm,
                'analysis' => 'DURL',
                'message'  => 'Too many requests. Please try again later.',
            ],
        ];

        // 4 & 5. Cluster limits — only when request matched a cluster
        if ($request->cluster_id !== null) {
            $checks[] = [
                'key'      => 'cluster:' . $request->cluster_id,
                'limit'    => $request->cluster_global_rpm,
                'analysis' => 'CGRL',
                'message'  => 'Cluster rate limit exceeded.',
            ];
            $checks[] = [
                'key'      => 'cluster:' . $request->cluster_id . ':' . $ip,
                'limit'    => $request->cluster_user_rpm,
                'analysis' => 'CURL',
                'message'  => 'Too many requests for this cluster. Please try again later.',
            ];
        }

        foreach ($checks as $check) {
            if ($check['limit'] === null || $check['limit'] <= 0) continue;

            if (RateLimiter::tooManyAttempts($check['key'], $check['limit'])) {
                logProcessor::dispatch((object) [
                    'logger_key'        => $request->logger_key,
                    'host'              => $host,
                    'url'               => $request->getRequestUri(),
                    'response_status'   => '429',
                    'response_headers'  => $check['message'],
                    'response_body'     => '',
                    'response_time'     => '0',
                    'dlp'               => '',
                    'dlp_count'         => '',
                    'analysis'          => $check['analysis'],
                    'request_method'    => $request->method(),
                    'request_body'      => $request->clean_body,
                    'middleware_response' => (microtime(true) - $request->pcm_1),
                ]);

                return response()->json([
                    'message'             => $check['message'],
                    'retry_after_seconds' => RateLimiter::availableIn($check['key']),
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($check['key']);
        }

        return $next($request);
    }
}
