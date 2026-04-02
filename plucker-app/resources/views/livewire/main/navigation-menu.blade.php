<div class="fixed top-0 left-0 h-screen w-56 bg-white border-r border-zinc-200 flex flex-col z-30">

    <!-- Logo -->
    <div class="h-14 flex items-center px-5 border-b border-zinc-200 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-mark class="h-6 w-6 text-zinc-900" />
            <span class="text-sm font-semibold text-zinc-900 tracking-tight">API Middleware</span>
        </a>
    </div>

    <!-- Nav items -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        @php
            $base = $domain == 'nav' ? [] : ['domain' => base64_encode($domain)];
            $qs   = '?start_datetime=' . request()->get('start_datetime') . '&end_datetime=' . request()->get('end_datetime');

            $navItems = [
                [
                    'label'  => 'Dashboard',
                    'route'  => 'dashboard',
                    'href'   => $domain == 'nav' ? route('dashboard') : route('dashboard', $base) . $qs,
                    'icon'   => '<path d="M12.0003 17.9998V14.9998M10.0703 2.81985L3.14027 8.36985C2.36027 8.98985 1.86027 10.2998 2.03027 11.2798L3.36027 19.2398C3.60027 20.6598 4.96027 21.8098 6.40027 21.8098H17.6003C19.0303 21.8098 20.4003 20.6498 20.6403 19.2398L21.9703 11.2798C22.1303 10.2998 21.6303 8.98985 20.8603 8.36985L13.9303 2.82985C12.8603 1.96985 11.1303 1.96985 10.0703 2.81985Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'label'  => 'API Clusters',
                    'route'  => 'api_cluster',
                    'href'   => route('api_cluster', $base) . $qs,
                    'icon'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9h18v-6h-18v18h6v-18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 15h12v-12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'label'  => 'Cluster Policies',
                    'route'  => 'api_cluster_policy',
                    'href'   => route('api_cluster_policy', $base) . $qs,
                    'icon'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 8l2 0M13 12l2 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
                ],
                [
                    'label'  => 'API Details',
                    'route'  => 'apis',
                    'href'   => route('apis', $base) . $qs,
                    'icon'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15h-6.5a2.5 2.5 0 1 1 0 -5h.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 12v6.5a2.5 2.5 0 1 1 -5 0v-.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 9h6.5a2.5 2.5 0 1 1 0 5h-.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 12v-6.5a2.5 2.5 0 0 1 5 0v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'label'  => 'Logs',
                    'route'  => 'logs',
                    'href'   => route('logs', $base) . $qs,
                    'icon'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 12h.01M4 6h.01M4 18h.01M8 18h2M8 12h2M8 6h2M14 6h6M14 12h6M14 18h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
                [
                    'label'  => 'Configuration',
                    'route'  => 'config',
                    'href'   => route('config', $base) . $qs,
                    'icon'   => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
                ],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ $item['href'] }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition-colors
                      {{ $active
                            ? 'bg-zinc-900 text-white font-medium'
                            : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     class="{{ $active ? 'text-white' : 'text-zinc-400' }}">
                    {!! $item['icon'] !!}
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach

    </nav>

    <!-- Bottom: settings link -->
    <div class="px-3 pb-4 border-t border-zinc-100 pt-3 shrink-0">
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-zinc-400">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ Auth::user()->name }}
        </a>
    </div>

</div>
