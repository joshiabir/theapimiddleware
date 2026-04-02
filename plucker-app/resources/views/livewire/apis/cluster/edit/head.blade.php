<div>
    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100">
            <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Cluster Details</p>
        </div>
        <div class="p-5">
            @if($update)
            <div class="flex items-center gap-2 p-3 mb-5 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                <span><span class="font-medium">Saved.</span> Cluster details updated.</span>
            </div>
            @endif

            <form wire:submit="save">
                <div class="space-y-4 mb-5">
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Cluster Name</label>
                        <input type="text" wire:model='name' value="{{ $name }}" required placeholder="e.g. Public APIs"
                               class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Description</label>
                        <textarea rows="3" wire:model='description' placeholder="Optional description"
                                  class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Policy</label>
                        <select wire:model='policy_id'
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($policy_list as $policies)
                                @if(count($policy_list) == 1)
                                    <option value="None">No Policy</option>
                                    <option value="{{ $policies->id }}">{{ $policies->name }} — {{ $policies->created_at }}</option>
                                @else
                                    <option value="{{ $policies->id }}">{{ $policies->name }} — {{ $policies->created_at }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-zinc-400">Or <a href="/" class="text-blue-600 hover:underline">create a new policy</a></p>
                    </div>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
