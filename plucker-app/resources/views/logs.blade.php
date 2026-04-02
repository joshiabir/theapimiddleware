<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">
        {{ __('Logs') }}
    </x-slot>
    <x-slot name='crumb'>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ms-1 text-sm font-medium text-zinc-500">Logs</span>
            </div>
        </li>
    </x-slot>

    @livewire('logs.logs', ['start_time' => $start_time, 'end_time' => $end_time, 'action' => '/logs', 'key' => $key, 'domain' => $domain])
</x-app-layout>
