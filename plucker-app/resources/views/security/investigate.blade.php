<x-app-layout :data="[$domain, $start_time, $end_time]">
    <x-slot name="page">
        {{ __('Investigate') }}
    </x-slot>
    <x-slot name="crumb">
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="/security" class="ms-1 text-sm font-medium text-zinc-500 hover:text-blue-600">Security</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ms-1 text-sm font-medium text-zinc-500">Investigate</span>
            </div>
        </li>
    </x-slot>

    @if($log == [] || $log instanceof \Illuminate\Support\Collection && $log->isEmpty())
        {{-- ── Incident list view (browsing by IP / API / type) ── --}}
        <div class="mb-4">
            <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-1">Investigating</p>
            <p class="text-sm font-mono text-zinc-700 font-semibold">{{ base64_decode(request()->key) }}</p>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100">
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">ID</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Time</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Type</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Endpoint</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Client</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse($incidents as $incident)
                        @php
                            $label = match($incident->type) {
                                'DNF'    => ['text' => 'Domain Not Found',              'desc' => 'The requested domain is not registered or is inactive in the gateway.',           'class' => 'bg-red-100 text-red-700'],
                                'HTPSF'  => ['text' => 'HTTPS Failure',                'desc' => 'Request was made over HTTP but this cluster requires HTTPS.',                      'class' => 'bg-red-100 text-red-700'],
                                'BADACS' => ['text' => 'Inactive API Access',          'desc' => 'Request reached an API or cluster that has been disabled.',                        'class' => 'bg-orange-100 text-orange-700'],
                                'IPWLF'  => ['text' => 'IP Allowlist Failure',         'desc' => 'The client IP is not on the allowlist for this cluster.',                          'class' => 'bg-red-100 text-red-700'],
                                'IPBLF'  => ['text' => 'IP Blocklist Hit',             'desc' => 'The client IP is on the blocklist for this cluster and was denied access.',        'class' => 'bg-red-100 text-red-700'],
                                'AUTHVF' => ['text' => 'Auth Validation Failure',      'desc' => 'No Authorization header or parameter was present on a cluster requiring auth.',   'class' => 'bg-red-100 text-red-700'],
                                'SRVRL'  => ['text' => 'Server Rate Limit',            'desc' => 'The server-wide absolute request ceiling was reached across all domains.',         'class' => 'bg-amber-100 text-amber-700'],
                                'DGRL'   => ['text' => 'Domain Global Rate Limit',     'desc' => 'Total requests across all users on this domain exceeded the domain-level cap.',   'class' => 'bg-amber-100 text-amber-700'],
                                'DURL'   => ['text' => 'Domain User Rate Limit',       'desc' => 'This client IP exceeded the per-user request limit set at the domain level.',     'class' => 'bg-amber-100 text-amber-700'],
                                'CGRL'   => ['text' => 'Cluster Global Rate Limit',    'desc' => 'Total requests to this cluster exceeded its configured global RPM cap.',          'class' => 'bg-amber-100 text-amber-700'],
                                'CURL'   => ['text' => 'Cluster User Rate Limit',      'desc' => 'This client IP exceeded the per-user RPM limit set on this cluster\'s policy.',  'class' => 'bg-amber-100 text-amber-700'],
                                'REQF'   => ['text' => 'Request Failed',               'desc' => 'The backend returned an error or was unreachable.',                               'class' => 'bg-amber-100 text-amber-700'],
                                'DLP'    => ['text' => 'DLP Triggered',                'desc' => 'A keyword or pattern match was found in the request or response body.',           'class' => 'bg-purple-100 text-purple-700'],
                                default  => ['text' => $incident->type,                'desc' => '',                                                                                 'class' => 'bg-zinc-100 text-zinc-600'],
                            };
                        @endphp
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-5 py-3 text-xs font-mono text-zinc-400">#{{ $incident->id }}</td>
                            <td class="px-5 py-3 text-xs font-mono text-zinc-500 whitespace-nowrap">{{ $incident->created_at }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $label['class'] }}">
                                    {{ $label['text'] }}
                                </span>
                                @if($label['desc'])
                                <p class="text-xs text-zinc-400 mt-0.5">{{ $label['desc'] }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-xs font-mono text-zinc-600 max-w-xs truncate">{{ $incident->url }}</td>
                            <td class="px-5 py-3 text-xs font-mono text-zinc-600">{{ $incident->client }}</td>
                            <td class="px-5 py-3 text-xs text-zinc-500">{{ $incident->status }}</td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->log_key), 'type' => 'key']) }}"
                                   class="text-xs text-blue-600 hover:underline font-medium">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-zinc-400">No incidents found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- ── Full log detail view ── --}}
        @foreach($log as $lo) @endforeach
        @php $client = preg_replace('/[\[\]\'"]/', '', $lo->client); @endphp

        {{-- Incident alerts --}}
        @foreach($incidents as $incident)
        @php
            $incidentText = match($incident->type) {
                'DNF'    => 'Domain Not Found — domain is not registered or inactive',
                'HTPSF'  => 'HTTPS Failure — request made over HTTP on an HTTPS-only cluster',
                'BADACS' => 'Inactive API Access — request hit a disabled API or cluster',
                'IPWLF'  => 'IP Allowlist Failure — client IP not on the allowlist',
                'IPBLF'  => 'IP Blocklist Hit — client IP is blocked on this cluster',
                'AUTHVF' => 'Auth Validation Failure — no Authorization header or parameter present',
                'SRVRL'  => 'Server Rate Limit — server-wide request ceiling reached',
                'DGRL'   => 'Domain Global Rate Limit — domain-level total RPM cap exceeded',
                'DURL'   => 'Domain User Rate Limit — per-user RPM limit exceeded at domain level',
                'CGRL'   => 'Cluster Global Rate Limit — cluster total RPM cap exceeded',
                'CURL'   => 'Cluster User Rate Limit — per-user RPM limit exceeded at cluster level',
                'REQF'   => 'Request Failed — backend returned an error or was unreachable',
                'DLP'    => 'DLP Triggered — keyword or pattern match found in request or response',
                default  => $incident->type,
            };
        @endphp
        <div class="flex items-center gap-2 p-3 mb-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <span>
                <span class="font-medium">Security Alert:</span>
                <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->type), 'type' => 'inc']) }}"
                   class="underline ml-1">{{ $incidentText }}</a>
            </span>
        </div>
        @endforeach

        {{-- Meta card --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-4">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Request Summary</p>
            </div>
            <div class="p-5 grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wide w-28 shrink-0">Endpoint</span>
                    <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($lo->url), 'type' => 'api']) }}"
                       class="text-xs font-mono text-blue-600 hover:underline truncate">{{ $lo->url }}</a>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wide w-28 shrink-0">Client IP</span>
                    <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($lo->client), 'type' => 'ip']) }}"
                       class="text-xs font-mono text-blue-600 hover:underline">{{ $client }}</a>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wide w-28 shrink-0">Status</span>
                    @php
                        $statusCode = (int) $lo->response_status;
                        $statusClass = match(true) {
                            $statusCode >= 500 => 'bg-red-100 text-red-700',
                            $statusCode >= 400 => 'bg-amber-100 text-amber-700',
                            $statusCode >= 200 => 'bg-green-100 text-green-700',
                            default            => 'bg-zinc-100 text-zinc-500',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium {{ $statusClass }}">
                        {{ $lo->response_status ?? '—' }}
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wide w-28 shrink-0">Analysis</span>
                    @php
                        $analysisClass = match($lo->analysis) {
                            'Controlled' => 'bg-blue-100 text-blue-700',
                            'Blocked'    => 'bg-red-100 text-red-700',
                            'Clean'      => 'bg-green-100 text-green-700',
                            default      => 'bg-zinc-100 text-zinc-500',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $analysisClass }}">
                        {{ $lo->analysis }}
                    </span>
                </div>
                <div class="flex items-baseline gap-2 col-span-2">
                    <span class="text-xs font-medium text-zinc-400 uppercase tracking-wide w-28 shrink-0">Timestamp</span>
                    <span class="text-xs font-mono text-zinc-500">{{ $lo->created_at }}</span>
                </div>
            </div>
        </div>

        {{-- DLP Results --}}
        @if($dlp && (int) $dlp->count > 0)
        @php $dlp_matches = json_decode($dlp->value, true); @endphp
        <div class="bg-white border border-red-200 rounded-xl overflow-hidden mb-4">
            <div class="px-5 py-3 border-b border-red-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <p class="text-xs text-red-600 uppercase tracking-wide font-medium">DLP — {{ $dlp->count }} Match{{ (int)$dlp->count !== 1 ? 'es' : '' }} Detected</p>
                </div>
            </div>
            <div class="p-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-100">
                            <th class="pb-2 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Type</th>
                            <th class="pb-2 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Matched Value</th>
                            <th class="pb-2 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Occurrences</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @if(is_array($dlp_matches))
                            @foreach($dlp_matches as $match)
                            <tr>
                                <td class="py-2 pr-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $match['type'] === 'keyword' ? 'bg-amber-100 text-amber-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($match['type']) }}
                                    </span>
                                </td>
                                <td class="py-2 pr-4 text-xs font-mono text-zinc-700">
                                    @if($match['type'] === 'pattern' && is_array($match['values']))
                                        {{ implode(', ', array_unique(array_merge(...array_values($match['values'])))) }}
                                    @else
                                        {{ $match['values'] }}
                                    @endif
                                </td>
                                <td class="py-2 text-right text-xs font-mono text-red-600 font-medium">{{ $match['count'] }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="py-3 text-xs text-zinc-400">No match details available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Headers + Bodies grid --}}
        <div class="grid grid-cols-2 gap-4">

            {{-- Request Headers --}}
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100">
                    <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Request Headers</p>
                </div>
                <div class="p-4 overflow-auto max-h-64">
                    @php $req_headers = json_decode($lo->request_headers, true); @endphp
                    @if($req_headers !== null && count($req_headers))
                        <table class="w-full text-xs font-mono">
                            @foreach($req_headers as $key => $value)
                            <tr class="border-b border-zinc-50 last:border-0">
                                <td class="py-1 pr-4 text-zinc-400 whitespace-nowrap align-top">{{ $key }}</td>
                                <td class="py-1 text-zinc-700 break-all">{{ is_array($value) ? $value[0] : $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="text-xs text-zinc-400">No request headers</p>
                    @endif
                </div>
            </div>

            {{-- Response Headers --}}
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100">
                    <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Response Headers</p>
                </div>
                <div class="p-4 overflow-auto max-h-64">
                    @php $res_headers = json_decode($lo->response_headers, true); @endphp
                    @if($res_headers !== null && count($res_headers))
                        <table class="w-full text-xs font-mono">
                            @foreach($res_headers as $key => $value)
                            <tr class="border-b border-zinc-50 last:border-0">
                                <td class="py-1 pr-4 text-zinc-400 whitespace-nowrap align-top">{{ $key }}</td>
                                <td class="py-1 text-zinc-700 break-all">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @elseif($lo->response_status == null)
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap">{{ $lo->response_headers }}</pre>
                    @else
                        <p class="text-xs text-zinc-400">No response headers</p>
                    @endif
                </div>
            </div>

            {{-- Request Parameters --}}
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100">
                    <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Request Parameters</p>
                </div>
                <div class="p-4 overflow-auto max-h-64">
                    @if(strlen($lo->prams) > 2)
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->prams), JSON_PRETTY_PRINT) }}</pre>
                    @else
                        <p class="text-xs text-zinc-400">No parameters</p>
                    @endif
                </div>
            </div>

            {{-- Request Body --}}
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100">
                    <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Request Body</p>
                </div>
                <div class="p-4 overflow-auto max-h-64">
                    @if(strlen($lo->request_body) > 2)
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->request_body), JSON_PRETTY_PRINT) }}</pre>
                    @else
                        <p class="text-xs text-zinc-400">Empty body</p>
                    @endif
                </div>
            </div>

            {{-- Response Body (full width) --}}
            <div class="col-span-2 bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-zinc-100">
                    <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Response Body</p>
                </div>
                <div class="p-4 overflow-auto max-h-96">
                    @if(strlen($lo->response_body) > 4)
                        @if(json_validate($lo->response_body))
                            <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->response_body), JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ $lo->response_body }}</pre>
                        @endif
                    @else
                        <p class="text-xs text-zinc-400">Empty body</p>
                    @endif
                </div>
            </div>

        </div>
    @endif

</x-app-layout>
