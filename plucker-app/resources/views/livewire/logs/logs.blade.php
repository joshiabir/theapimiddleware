<div>
    {{-- Active filter chips --}}
    @if($url != '%25' && $url != 'Any' || $method != 'Any' || $status != 'Any' || $user != 'Any')
    <div class="flex flex-wrap gap-2 mb-4">
        @if($url != '%25' && $url != 'Any')
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&method={{ $method_see }}&status={{ $status_see }}&user={{ $user }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-900 text-white text-xs font-medium rounded-md hover:bg-zinc-700 transition-colors">
                <span class="text-zinc-400 uppercase tracking-wide text-[10px]">URL</span>
                <span class="font-mono truncate max-w-xs">{{ $url_see }}</span>
                <svg class="w-3 h-3 ml-1 text-zinc-400" viewBox="0 0 24 24" fill="currentColor"><path d="M6.4 19L5 17.6L10.6 12L5 6.4L6.4 5L12 10.6L17.6 5L19 6.4L13.4 12L19 17.6L17.6 19L12 13.4L6.4 19Z"/></svg>
            </a>
        @endif
        @if($method != 'Any')
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&status={{ $status }}&user={{ $user }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-900 text-white text-xs font-medium rounded-md hover:bg-zinc-700 transition-colors">
                <span class="text-zinc-400 uppercase tracking-wide text-[10px]">Method</span>
                <span>{{ $method_see }}</span>
                <svg class="w-3 h-3 ml-1 text-zinc-400" viewBox="0 0 24 24" fill="currentColor"><path d="M6.4 19L5 17.6L10.6 12L5 6.4L6.4 5L12 10.6L17.6 5L19 6.4L13.4 12L19 17.6L17.6 19L12 13.4L6.4 19Z"/></svg>
            </a>
        @endif
        @if($status != 'Any')
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&method={{ $method }}&user={{ $user }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-900 text-white text-xs font-medium rounded-md hover:bg-zinc-700 transition-colors">
                <span class="text-zinc-400 uppercase tracking-wide text-[10px]">Status</span>
                <span class="font-mono">{{ $status_see }}</span>
                <svg class="w-3 h-3 ml-1 text-zinc-400" viewBox="0 0 24 24" fill="currentColor"><path d="M6.4 19L5 17.6L10.6 12L5 6.4L6.4 5L12 10.6L17.6 5L19 6.4L13.4 12L19 17.6L17.6 19L12 13.4L6.4 19Z"/></svg>
            </a>
        @endif
        @if($user != 'Any')
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&method={{ $method }}&status={{ $status }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-900 text-white text-xs font-medium rounded-md hover:bg-zinc-700 transition-colors">
                <span class="text-zinc-400 uppercase tracking-wide text-[10px]">User</span>
                <span class="font-mono">{{ $user_see }}</span>
                <svg class="w-3 h-3 ml-1 text-zinc-400" viewBox="0 0 24 24" fill="currentColor"><path d="M6.4 19L5 17.6L10.6 12L5 6.4L6.4 5L12 10.6L17.6 5L19 6.4L13.4 12L19 17.6L17.6 19L12 13.4L6.4 19Z"/></svg>
            </a>
        @endif
    </div>
    @endif

    {{-- Logs table --}}
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100">
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide whitespace-nowrap">Timestamp</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Endpoint</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide">Method</th>
                        <th class="px-4 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide whitespace-nowrap">Resp. Time</th>
                        <th class="px-4 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Latency</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">User</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide">Analysis</th>
                        <th class="px-4 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($logs as $log)
                    @php
                        $statusCode = (int) $log->response_status;
                        $statusClass = match(true) {
                            $statusCode >= 500 => 'bg-red-100 text-red-700',
                            $statusCode >= 400 => 'bg-amber-100 text-amber-700',
                            $statusCode >= 300 => 'bg-zinc-100 text-zinc-600',
                            $statusCode >= 200 => 'bg-green-100 text-green-700',
                            default            => 'bg-zinc-100 text-zinc-500',
                        };
                        $methodClass = match($log->request_method) {
                            'GET'    => 'bg-blue-100 text-blue-700',
                            'POST'   => 'bg-green-100 text-green-700',
                            'PUT'    => 'bg-amber-100 text-amber-700',
                            'PATCH'  => 'bg-indigo-100 text-indigo-700',
                            'DELETE' => 'bg-red-100 text-red-700',
                            default  => 'bg-zinc-100 text-zinc-600',
                        };
                        $analysisClass = match($log->analysis) {
                            'Controlled' => 'bg-blue-100 text-blue-700',
                            'Blocked'    => 'bg-red-100 text-red-700',
                            'Clean'      => 'bg-green-100 text-green-700',
                            default      => 'bg-zinc-100 text-zinc-500',
                        };
                        $client = preg_replace('/[\[\]\'"]/', '', $log->client);
                    @endphp
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-4 py-2.5 text-xs text-zinc-400 font-mono whitespace-nowrap">
                            {{ $log->created_at }}
                        </td>
                        <td class="px-4 py-2.5 max-w-sm">
                            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $log->url }}&status={{ $status }}&method={{ $method }}&user={{ $user }}"
                               class="text-xs font-mono text-zinc-700 hover:text-blue-600 truncate block">
                                {{ $log->url }}
                            </a>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?method={{ $log->request_method }}&status={{ $status }}&end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&user={{ $user }}">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $methodClass }}">
                                    {{ $log->request_method }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-2.5 text-right text-xs text-zinc-600 font-mono whitespace-nowrap">
                            {{ number_format(((float) $log->response_time) * 1000, 0) }}ms
                        </td>
                        <td class="px-4 py-2.5 text-right text-xs text-zinc-600 font-mono whitespace-nowrap">
                            {{ number_format(((float) $log->middleware_response) * 1000, 0) }}ms
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&method={{ $method }}&status={{ $log->response_status }}&user={{ $user }}">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium {{ $statusClass }}">
                                    {{ $log->response_status ?? '—' }}
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-2.5">
                            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&url={{ $url }}&method={{ $method }}&status={{ $status }}&user={{ $client }}"
                               class="text-xs text-zinc-600 hover:text-blue-600 font-mono truncate block max-w-[10rem]">
                                {{ $client }}
                            </a>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            @if($log->analysis == 'Controlled')
                                <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&key={{ base64_encode($log->key) }}&page={{ $logs->currentPage() }}&url={{ $url }}&method={{ $method }}&status={{ $status }}&user={{ $user }}&dlp=true#{{ base64_encode($log->created_at) }}">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $analysisClass }}">
                                        {{ $log->analysis }}
                                    </span>
                                </a>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $analysisClass }}">
                                    {{ $log->analysis }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <a href="{{ route('log_investigate', ['domain' => base64_encode($domain)]) }}?key={{ base64_encode($log->key) }}&type=key"
                               class="text-xs text-blue-600 hover:underline font-medium whitespace-nowrap">
                                View
                            </a>
                        </td>
                    </tr>

                    @if(base64_encode($log->key) == $key)
                        </tbody></table>
                        <livewire:logs.details :log="$key_val" :dlp="$dlp" :domain="$domain" />
                        <table class="w-full text-sm"><tbody>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination + download --}}
        <div class="px-4 py-3 border-t border-zinc-100 flex items-center justify-between">
            <button wire:click="download"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-zinc-900 hover:bg-zinc-700 text-white text-xs font-medium rounded-md transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M7 10L12 15M12 15L17 10M12 15V3"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export CSV
            </button>
            <div>{{ $logs->links() }}</div>
        </div>
    </div>
</div>
