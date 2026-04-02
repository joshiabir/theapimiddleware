<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-zinc-100">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Top Clients</p>
    </div>
    <div class="divide-y divide-zinc-50">
        @forelse($clients->take(10) as $client)
        @php $ip = preg_replace('/[\[\]\'"]/', '', $client->client); @endphp
        <div class="px-5 py-2.5 flex items-center justify-between hover:bg-zinc-50 transition-colors">
            <a href="{{ route('logs', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&user={{ $ip }}"
               class="text-xs font-mono text-zinc-700 hover:text-blue-600 truncate max-w-[180px]">{{ $ip }}</a>
            <span class="text-xs font-medium tabular-nums text-zinc-500 ml-3 shrink-0">{{ number_format($client->count) }}</span>
        </div>
        @empty
        <div class="px-5 py-10 text-center text-xs text-zinc-400">No clients in this period</div>
        @endforelse
    </div>
</div>
