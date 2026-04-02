<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">
        {{ __('Incidents') }}
    </x-slot>
    <x-slot name='crumb'>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <a href="/security" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Security</a>
            </div>
        </li>

        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Incidents</span>
            </div>
        </li>
    </x-slot>
    <div class="">
        <div class="">
            <div class=" overflow-hidden sm:rounded-lg">
            @livewire('security.incident.feed', ['domain'=>$domain])
            </div>
        </div>
    </div>
    </div>
</x-app-layout>