<div class="h-14 flex items-center px-5 gap-3">

    {{-- Domain selector --}}
    @foreach($domain_selected as $domain)
    @php
        $isActive = $domain->status === 'Active';
        $policy   = $domain->policy === 'NULL' ? 'No' : $domain->policy;
    @endphp

    <div class="flex items-center gap-2">

        {{-- Domain pill + dropdown trigger --}}
        <div class="relative">
            <button id="dropdownHoverButton"
                    data-dropdown-toggle="dropdownHover"
                    data-dropdown-trigger="click"
                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-zinc-900 text-white text-xs font-medium rounded-md hover:bg-zinc-700 transition-colors">
                <span>{{ $domain->host }}</span>
                <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 14 8">
                    <path d="M1 1L7 7L13 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            {{-- Domain dropdown --}}
            <div id="dropdownHover"
                 class="z-50 hidden absolute top-full mt-1 left-0 bg-white border border-zinc-200 rounded-lg shadow-lg w-52 py-1">
                @foreach($domains as $do)
                <a href="{{ route(request()->route()->getName(), ['domain' => base64_encode($do->host), 'start_datetime' => $start_time, 'end_datetime' => $end_time]) }}"
                   class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 truncate">
                    {{ $do->host }}
                </a>
                @endforeach
                <div class="border-t border-zinc-100 mt-1 pt-1">
                    <a href="/init"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-zinc-50">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Add new domain
                    </a>
                </div>
            </div>
        </div>

        {{-- Status badge --}}
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                     {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $isActive ? 'bg-green-500' : 'bg-zinc-400' }}"></span>
            {{ $domain->status }}
        </span>

    </div>
    @endforeach

    {{-- Date range picker --}}
    <div class="flex items-center gap-2 ml-2">
        <form action="" class="flex items-center gap-2">
            <div id="date-range-picker" date-rangepicker datepicker-format="yyyy-mm-dd" class="flex items-center gap-2">

                <input id="datepicker-range-start"
                       name="start_datetime"
                       type="text"
                       value="{{ $start_time }}"
                       datepicker-format="yyyy-mm-dd"
                       wire:model="start_date"
                       placeholder="Start date"
                       class="text-xs text-zinc-700 bg-zinc-50 border border-zinc-200 rounded-md px-3 py-1.5 w-32 text-center focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

                <span class="text-xs text-zinc-400">to</span>

                <input id="datepicker-range-end"
                       name="end_datetime"
                       type="text"
                       value="{{ $end_time }}"
                       datepicker-format="yyyy-mm-dd"
                       wire:model="end_date"
                       placeholder="End date"
                       class="text-xs text-zinc-700 bg-zinc-50 border border-zinc-200 rounded-md px-3 py-1.5 w-32 text-center focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

            </div>

            <input type="hidden" name="api" value="{{ base64_encode($api) }}">

            <button type="submit"
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                Fetch
            </button>
        </form>
    </div>

    {{-- Right side: notifications + user --}}
    <div class="ml-auto flex items-center gap-3">

        {{-- Notifications --}}
        @livewire('main.notifications', ['domain' => $domain_selected])

        {{-- User menu --}}
        <div class="relative flex items-center gap-2">

            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <img class="h-7 w-7 rounded-full object-cover ring-1 ring-zinc-200"
                 src="{{ Auth::user()->profile_photo_url }}"
                 alt="{{ Auth::user()->name }}">
            @endif

            <button id="dropdownUserAvatarButton"
                    data-dropdown-toggle="dropdownAvatar"
                    class="flex items-center gap-1.5 text-sm text-zinc-700 hover:text-zinc-900 transition-colors">
                <span class="text-xs font-medium">{{ Auth::user()->name }}</span>
                <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 14 8">
                    <path d="M1 1L7 7L13 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            {{-- User dropdown --}}
            <div id="dropdownAvatar"
                 class="z-50 hidden absolute top-full mt-1 right-0 bg-white border border-zinc-200 rounded-lg shadow-lg w-48 py-1">
                <div class="px-4 py-2.5 border-b border-zinc-100">
                    <p class="text-xs font-medium text-zinc-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-zinc-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('profile.show') }}"
                   class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                    Profile
                </a>
                <div class="border-t border-zinc-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a href="{{ route('logout') }}"
                           @click.prevent="$root.submit()"
                           class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            Sign out
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
