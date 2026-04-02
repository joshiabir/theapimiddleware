<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-zinc-100">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Top Endpoints</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100">
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Endpoint</th>
                    <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Cluster</th>
                    <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Requests</th>
                    <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Clients</th>
                    <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Blocked</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($apis as $api)
                <tr class="hover:bg-zinc-50 transition-colors">
                    <td class="px-5 py-3 max-w-xs">
                        <a href="{{ route('apis', ['domain' => base64_encode($domain)]) }}?api={{ base64_encode($api->url) }}"
                           class="text-xs font-mono text-zinc-700 hover:text-blue-600 truncate block">{{ $api->url }}</a>
                    </td>
                    <td class="px-5 py-3 text-xs text-zinc-500">{{ $api->cluster_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-right text-xs tabular-nums text-zinc-700">{{ number_format($api->total_logs) }}</td>
                    <td class="px-5 py-3 text-right text-xs tabular-nums text-zinc-700">{{ number_format($api->clients) }}</td>
                    <td class="px-5 py-3 text-right text-xs tabular-nums {{ $api->blocked_logs > 0 ? 'text-red-500 font-medium' : 'text-zinc-400' }}">
                        {{ number_format($api->blocked_logs) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-xs text-zinc-400">No API activity in this period</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
