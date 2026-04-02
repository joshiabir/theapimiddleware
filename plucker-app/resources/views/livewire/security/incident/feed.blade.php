<div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">

    <div class="px-5 py-3 border-b border-zinc-100 flex items-center justify-between">
        <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Security Events</p>
        <a href="{{ route('investigate', ['domain' => base64_encode($domain)]) }}" class="text-xs text-blue-600 hover:underline">View All</a>
    </div>

    <div class="divide-y divide-zinc-100 overflow-y-auto max-h-80">
        @forelse($inc as $incident)
        @php
            $label = match($incident->type) {
                'DNF'    => ['text' => 'Domain Not Found',        'class' => 'bg-red-100 text-red-700'],
                'HTPSF'  => ['text' => 'HTTPS Failure',           'class' => 'bg-red-100 text-red-700'],
                'BADACS' => ['text' => 'Inactive API',            'class' => 'bg-orange-100 text-orange-700'],
                'IPWLF'  => ['text' => 'IP Allowlist Failure',    'class' => 'bg-red-100 text-red-700'],
                'IPBLF'  => ['text' => 'IP Blocklist Hit',        'class' => 'bg-red-100 text-red-700'],
                'AUTHVF' => ['text' => 'Auth Failure',            'class' => 'bg-red-100 text-red-700'],
                'SRVRL'  => ['text' => 'Server Rate Limit',       'class' => 'bg-amber-100 text-amber-700'],
                'DGRL'   => ['text' => 'Domain Rate Limit',       'class' => 'bg-amber-100 text-amber-700'],
                'DURL'   => ['text' => 'Domain User Rate Limit',  'class' => 'bg-amber-100 text-amber-700'],
                'CGRL'   => ['text' => 'Cluster Rate Limit',      'class' => 'bg-amber-100 text-amber-700'],
                'CURL'   => ['text' => 'Cluster User Rate Limit', 'class' => 'bg-amber-100 text-amber-700'],
                'REQF'   => ['text' => 'Request Failed',          'class' => 'bg-zinc-100 text-zinc-600'],
                'DLP'    => ['text' => 'DLP Triggered',           'class' => 'bg-purple-100 text-purple-700'],
                default  => ['text' => $incident->type,           'class' => 'bg-zinc-100 text-zinc-600'],
            };
        @endphp
        <div class="px-5 py-3 hover:bg-zinc-50 transition-colors">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->type), 'type' => 'inc']) }}">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $label['class'] }}">
                            {{ $label['text'] }}
                        </span>
                    </a>
                    <p class="text-xs text-zinc-500 mt-1 truncate">
                        <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->client), 'type' => 'ip']) }}"
                           class="font-medium text-zinc-700 hover:text-blue-600">
                            {{ preg_replace('/[\[\]\'"]/', '', $incident->client) }}
                        </a>
                        <span class="text-zinc-300 mx-1">·</span>
                        <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->url), 'type' => 'api']) }}"
                           class="font-mono text-zinc-400 hover:text-blue-600">
                            {{ $incident->url }}
                        </a>
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1 shrink-0">
                    <span class="text-xs text-zinc-400">{{ \Carbon\Carbon::parse($incident->created_at)->diffForHumans() }}</span>
                    <a href="{{ route('investigate', ['domain' => base64_encode($domain), 'key' => base64_encode($incident->log_key), 'type' => 'key']) }}"
                       class="text-xs text-blue-600 hover:underline">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="px-5 py-10 text-center">
            <p class="text-sm text-zinc-400">No incidents in this period</p>
        </div>
        @endforelse
    </div>

    @if($inc->hasPages())
    <div class="px-5 py-3 border-t border-zinc-100">
        {{ $inc->links() }}
    </div>
    @endif

</div>
