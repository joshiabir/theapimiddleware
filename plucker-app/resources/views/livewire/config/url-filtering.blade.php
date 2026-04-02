<div>
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">URL Filtering</p>
        </div>
        <div class="p-5">
            @if($update == 'true')
            <div class="flex items-center gap-2 p-3 mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span><span class="font-medium">Policy saved.</span> URL filtering is now active.</span>
            </div>
            @endif

            <form wire:submit="save">
                <div class="mb-5">
                    <label class="block mb-2 text-xs font-medium text-zinc-500 uppercase tracking-wide">List Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="lists" value="white" wire:model='type'
                                   class="w-4 h-4 text-blue-600 border-zinc-300 focus:ring-blue-500" checked/>
                            <span class="text-sm text-zinc-700">Allowlist</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="lists" value="black" wire:model='type'
                                   class="w-4 h-4 text-blue-600 border-zinc-300 focus:ring-blue-500"/>
                            <span class="text-sm text-zinc-700">Blocklist</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="lists" value="NULL" wire:model='type'
                                   class="w-4 h-4 text-blue-600 border-zinc-300 focus:ring-blue-500"/>
                            <span class="text-sm text-zinc-700">Disabled</span>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">URL / API Path List <span class="normal-case">(one per line)</span></label>
                    <textarea rows="5" wire:model.fill='list'
                              class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"
                              placeholder="/api/v1/public&#10;/api/v2/*">@php foreach($apis as $api) { echo "$api->url\n"; } @endphp</textarea>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Save Configuration
                </button>
            </form>
        </div>
    </div>
</div>
