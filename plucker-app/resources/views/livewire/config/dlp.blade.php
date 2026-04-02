<div>
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Data Loss Prevention (DLP)</p>
        </div>
        <div class="p-5">
            @if($update == 'true')
            <div class="flex items-center gap-2 p-3 mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span><span class="font-medium">Policy saved.</span> DLP policy is now active.</span>
            </div>
            @endif

            {{-- Info banner --}}
            <div class="flex items-start gap-3 px-4 py-3 mb-5 bg-blue-50 border border-blue-100 rounded-lg">
                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div class="text-xs text-blue-700 space-y-1">
                    <p><span class="font-semibold">DLP scans both the request body and the response body</span> of every API call passing through this gateway.</p>
                    <p>Use <span class="font-semibold">Keywords</span> to match exact words or strings (e.g. <span class="font-mono">password</span>, <span class="font-mono">secret</span>). Use <span class="font-semibold">Patterns</span> to match formats with a regular expression (e.g. credit card numbers: <span class="font-mono">\d{"{16}"}</span>, SSNs: <span class="font-mono">\d{"{3}"}-\d{"{2}"}-\d{"{4}"}</span>).</p>
                    <p>The action taken on a match is set per-cluster under <span class="font-semibold">Clusters → Edit Policy → Security → DLP</span>. This page defines the <span class="italic">detection list</span> — what to look for across all clusters on this domain.</p>
                </div>
            </div>

            <form wire:submit="save">
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Keyword List <span class="normal-case">(one per line)</span></label>
                        <textarea rows="6" wire:model.fill='keyword'
                                  class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"
                                  placeholder="password&#10;secret&#10;api_key">@php foreach($keywords as $keyword) { echo "$keyword->value\n"; } @endphp</textarea>
                        <p class="text-xs text-zinc-400 mt-1.5">Exact string matches — case-insensitive. One word or phrase per line.</p>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Pattern List <span class="normal-case">(regex, one per line)</span></label>
                        <textarea rows="6" wire:model.fill='pattern'
                                  class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"
                                  placeholder="\d{16}&#10;[A-Z]{2}\d{6}">@php foreach($patterns as $pattern) { echo "$pattern->value\n"; } @endphp</textarea>
                        <p class="text-xs text-zinc-400 mt-1.5">PHP-compatible regular expressions. Useful for structured data like card numbers, IDs, emails.</p>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Exception List — APIs excluded from DLP <span class="normal-case">(one per line)</span></label>
                    <textarea rows="4" wire:model.fill='list'
                              class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"
                              placeholder="/api/v1/health&#10;/api/v1/status">@php foreach($apis as $api) { echo "$api->url\n"; } @endphp</textarea>
                    <p class="text-xs text-zinc-400 mt-1.5">DLP scanning is skipped entirely for these API paths. Use for health checks, public endpoints, or high-volume low-risk routes where scanning overhead isn't justified.</p>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Save Configuration
                </button>
            </form>
        </div>
    </div>
</div>
