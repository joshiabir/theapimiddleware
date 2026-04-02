<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">{{ __('APIs') }}</x-slot>
    <x-slot name='crumb'></x-slot>

    <div class="space-y-4">
        {{-- Endpoint selector --}}
        @livewire('apis.simple-list', ['key' => $api, 'domain' => $domain, 'start_time' => $start_time, 'end_time' => $end_time])

        @if($api)
        {{-- KPI strip + traffic chart --}}
        @livewire('apis.total_requests', ['url' => $api, 'start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])

        {{-- Breakdown row --}}
        <div class="grid grid-cols-3 gap-4">
            @livewire('apis.response_code', ['url' => $api, 'start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
            @livewire('apis.methods',       ['url' => $api, 'start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
            @livewire('apis.clients',       ['url' => $api, 'start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
        @else
        <div class="bg-white border border-zinc-200 rounded-xl px-6 py-16 text-center">
            <p class="text-sm text-zinc-400">Select an endpoint from the dropdown above to view analytics.</p>
        </div>
        @endif
    </div>
</x-app-layout>
