<div>
    <div class="flex items-center gap-2">
        {{-- Current selection --}}
        <div class="flex-1 px-4 py-2.5 bg-white border border-zinc-200 rounded-lg text-sm font-mono text-zinc-700 truncate">
            @if($key)
                {{ $key }}
            @else
                <span class="text-zinc-400">Select an API endpoint...</span>
            @endif
        </div>

        {{-- Dropdown trigger --}}
        <div class="relative">
            <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers" data-dropdown-placement="bottom-end" type="button"
                    class="inline-flex items-center gap-2 px-3 py-2.5 bg-white border border-zinc-200 hover:bg-zinc-50 text-zinc-600 text-sm font-medium rounded-lg transition-colors">
                <span>Browse</span>
                <svg class="w-3.5 h-3.5" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L7 7L13 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div id="dropdownUsers" class="z-20 hidden absolute right-0 mt-1 bg-white border border-zinc-200 rounded-xl shadow-lg min-w-72">
                <ul class="max-h-72 overflow-y-auto py-1 text-sm" aria-labelledby="dropdownUsersButton">
                    @foreach($list as $res)
                    <li>
                        <a href="{{ route('apis', ['domain' => base64_encode($domain)]) }}?start_datetime={{ $start_time }}&end_datetime={{ $end_time }}&api={{ base64_encode(trim($res->url)) }}"
                           class="block px-4 py-2.5 font-mono text-xs text-zinc-700 hover:bg-zinc-50 hover:text-blue-600 transition-colors truncate">
                            {{ $res->url }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
