<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">{{ __('Edit Policy') }}</x-slot>
    <x-slot name='crumb'>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="/APIs" class="ms-1 text-sm font-medium text-zinc-500 hover:text-blue-600">APIs</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="{{ route('api_cluster', ['domain' => base64_encode($domain)]) }}?start_datetime={{ request()->get('start_datetime') }}&end_datetime={{ request()->get('end_datetime') }}"
                   class="ms-1 text-sm font-medium text-zinc-500 hover:text-blue-600">Clusters</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="{{ route('api_cluster_policy', ['domain' => base64_encode($domain)]) }}?start_datetime={{ request()->get('start_datetime') }}&end_datetime={{ request()->get('end_datetime') }}"
                   class="ms-1 text-sm font-medium text-zinc-500 hover:text-blue-600">Policies</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ms-1 text-sm font-medium text-zinc-500">Edit</span>
            </div>
        </li>
    </x-slot>

    <div class="max-w-3xl">
        @livewire('apis.cluster.edit.policy.all', ['policy_id' => $policy_id, 'host' => $domain])
    </div>
</x-app-layout>
