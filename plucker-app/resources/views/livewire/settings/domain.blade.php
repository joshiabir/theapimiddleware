<div>
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Domain Configuration</p>
        </div>
        <div class="p-5">
            @if($update == 'true')
            <div class="flex items-center gap-2 p-3 mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span><span class="font-medium">Settings saved.</span> Domain configuration is now active.</span>
            </div>
            @endif

            {{-- Info banner --}}
            <div class="flex items-start gap-3 px-4 py-3 mb-5 bg-blue-50 border border-blue-100 rounded-lg">
                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div class="text-xs text-blue-700 space-y-1">
                    <p>This domain is the <span class="font-semibold">entry point</span> for your gateway. All incoming requests to the gateway domain are forwarded to the target backend using the protocol you choose.</p>
                    <p>The <span class="font-semibold">Gateway Domain</span> is fixed — it is determined by your DNS A record pointing to this server. To change it, register a new domain instead.</p>
                </div>
            </div>

            <form wire:submit="save">
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Gateway Domain</label>
                        <input type="text" disabled value="{{ $host }}"
                               class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-400 cursor-not-allowed font-mono"/>
                        <p class="text-xs text-zinc-400 mt-1.5">The public domain pointing to this gateway. Read-only — change via DNS.</p>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Backend Target / IP</label>
                        <input type="text" wire:model='ip' value="{{ $ip }}" required
                               class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                        <p class="text-xs text-zinc-400 mt-1.5">IP address or hostname of your actual API server. Requests are proxied here.</p>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Backend Protocol</label>
                        <select wire:model='protocol' required
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="http">HTTP</option>
                            <option value="https">HTTPS</option>
                            <option value="mirror">Mirror (forward as received)</option>
                        </select>
                        <p class="text-xs text-zinc-400 mt-1.5">Protocol used when connecting to your backend. Use <span class="font-mono">Mirror</span> to preserve whatever the client sent.</p>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Gateway Mode</label>
                        <select wire:model='discover' required
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Discovery">Discovery</option>
                            <option value="Active">Active</option>
                        </select>
                        <p class="text-xs text-zinc-400 mt-1.5"><span class="font-medium text-zinc-600">Discovery</span> — forwards all requests and maps APIs automatically. Ideal when you first set up. <span class="font-medium text-zinc-600">Active</span> — only forwards requests to APIs that are registered in a cluster. Unknown paths are blocked.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        Save Changes
                    </button>
                    <button type="button" wire:click="delete"
                            wire:confirm="Are you sure you want to delete this domain? This will also make all records unavailable."
                            class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 text-sm font-medium border border-red-200 rounded-md transition-colors">
                        Delete Domain
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
