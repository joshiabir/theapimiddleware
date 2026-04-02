<div>
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Rate Limit</p>
        </div>
        <div class="p-5">
            @if($update == 'true')
            <div class="flex items-center gap-2 p-3 mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span><span class="font-medium">Settings saved.</span> Rate limit is now active.</span>
            </div>
            @endif

            {{-- Info banner --}}
            <div class="flex items-start gap-3 px-4 py-3 mb-5 bg-blue-50 border border-blue-100 rounded-lg">
                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div class="text-xs text-blue-700 space-y-1">
                    <p>This is the <span class="font-semibold">domain-level default</span> rate limit. It applies to all traffic on this domain when no cluster policy overrides it.</p>
                    <p>Individual clusters can have their own stricter limits set under <span class="font-semibold">Clusters → Edit Policy → Throttling</span>. Both limits are enforced independently — a request must pass the domain limit <span class="italic">and</span> the cluster limit.</p>
                </div>
            </div>

            <form wire:submit="save">
                <div class="mb-5 max-w-xs">
                    <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Requests per minute (per user)</label>
                    <input type="number" wire:model='limit' min="1" max="{{ $max_rpm }}" required
                           class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g. 60"/>
                    <p class="text-xs text-zinc-400 mt-1.5">Each unique IP address is limited to this many requests per minute. The total domain cap is automatically set to 10× this value. Maximum allowed by your license: <span class="font-medium text-zinc-600">{{ $max_rpm }} RPM</span></p>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
