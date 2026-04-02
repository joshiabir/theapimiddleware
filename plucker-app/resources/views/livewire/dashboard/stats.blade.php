<div class="grid grid-cols-6 gap-4">
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Total Requests</p>
        <h4 class="text-2xl font-semibold text-zinc-900">{{ number_format($total) }}</h4>
    </div>
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Blocked</p>
        <h4 class="text-2xl font-semibold {{ $blocked > 0 ? 'text-red-500' : 'text-zinc-900' }}">{{ number_format($blocked) }}</h4>
    </div>
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">DLP Events</p>
        <h4 class="text-2xl font-semibold {{ $dlp > 0 ? 'text-purple-600' : 'text-zinc-900' }}">{{ number_format($dlp) }}</h4>
    </div>
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Avg Response</p>
        <h4 class="text-2xl font-semibold text-zinc-900">{{ number_format($avg_rt) }}<span class="text-sm font-normal text-zinc-400 ml-1">ms</span></h4>
    </div>
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Error Rate</p>
        <h4 class="text-2xl font-semibold {{ $error_rate > 5 ? 'text-amber-500' : 'text-zinc-900' }}">{{ $error_rate }}<span class="text-sm font-normal text-zinc-400 ml-0.5">%</span></h4>
    </div>
    <div class="bg-white border border-zinc-200 rounded-xl p-5">
        <p class="text-xs text-zinc-400 uppercase tracking-wide font-medium mb-2">Unique Clients</p>
        <h4 class="text-2xl font-semibold text-zinc-900">{{ number_format($unique_clients) }}</h4>
    </div>
</div>
