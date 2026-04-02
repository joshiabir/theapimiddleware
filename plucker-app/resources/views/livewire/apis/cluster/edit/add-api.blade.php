<div id="default-modal" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <form wire:submit='add'>
            <div class="bg-white rounded-xl border border-zinc-200 shadow-lg">
                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                    <p class="text-sm font-semibold text-zinc-800">Add APIs to Cluster</p>
                    <button type="button" data-modal-hide="default-modal"
                            class="text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 rounded-md p-1.5 transition-colors">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <table id="default-table_1" class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-100">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide w-10"></th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Endpoint</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Added</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50">
                            @foreach($api_array_o as $api)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <input type="checkbox" wire:model="api_ids" value="{{ $api->api_id }}"
                                           class="w-4 h-4 text-blue-600 bg-zinc-50 border-zinc-300 rounded focus:ring-blue-500"/>
                                </td>
                                <td class="px-4 py-2.5 text-xs font-mono text-zinc-700">{{ $api->url }}</td>
                                <td class="px-4 py-2.5 text-xs text-zinc-400">{{ $api->created_at }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600">Unassigned</span>
                                </td>
                            </tr>
                            @endforeach
                            @foreach($api_array_f as $api)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <input type="checkbox" wire:model="api_ids" value="{{ $api->api_id }}"
                                           class="w-4 h-4 text-blue-600 bg-zinc-50 border-zinc-300 rounded focus:ring-blue-500"/>
                                </td>
                                <td class="px-4 py-2.5 text-xs font-mono text-zinc-700">{{ $api->url }}</td>
                                <td class="px-4 py-2.5 text-xs text-zinc-400">{{ $api->created_at }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">New</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="flex items-center gap-3 px-5 py-4 border-t border-zinc-100">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Add Selected
                    </button>
                    <button data-modal-hide="default-modal" type="button"
                            class="px-4 py-2 bg-white hover:bg-zinc-50 text-zinc-700 text-sm font-medium border border-zinc-200 rounded-md transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
