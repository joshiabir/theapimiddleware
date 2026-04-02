<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-zinc-100">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Error Codes</p>
    </div>
    <div class="divide-y divide-zinc-50">
        @forelse($error_responses as $response)
        @php
            $code      = (int) $response->response_status;
            $codeClass = match(true) {
                $code >= 500 => 'bg-red-100 text-red-700',
                $code >= 400 => 'bg-amber-100 text-amber-700',
                default      => 'bg-zinc-100 text-zinc-600',
            };
        @endphp
        <div class="px-5 py-2.5 flex items-center justify-between hover:bg-zinc-50 transition-colors">
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&status={{ $response->response_status }}"
               class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium {{ $codeClass }} hover:opacity-80">
                {{ $response->response_status }}
            </a>
            <span class="text-xs font-medium tabular-nums text-zinc-500 ml-3 shrink-0">{{ number_format($response->count) }}</span>
        </div>
        @empty
        <div class="px-5 py-10 text-center text-xs text-zinc-400">No errors in this period</div>
        @endforelse
    </div>
</div>
