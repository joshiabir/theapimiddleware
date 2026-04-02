<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">{{ __('Dashboard') }}</x-slot>
    <x-slot name="crumb"></x-slot>

    {{-- KPI strip --}}
    @livewire('dashboard.stats', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])

    {{-- Traffic chart + Security breakdown --}}
    <div class="grid grid-cols-3 gap-4 mt-4">
        <div class="col-span-2">
            @livewire('dashboard.total_requests', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
        <div class="col-span-1">
            @livewire('dashboard.protection', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
    </div>

    {{-- Response time + Top endpoints --}}
    <div class="grid grid-cols-3 gap-4 mt-4">
        <div class="col-span-1">
            @livewire('dashboard.response_time', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
        <div class="col-span-2">
            @livewire('dashboard.api_list', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
    </div>

    {{-- Top clients + HTTP methods + Error codes --}}
    <div class="grid grid-cols-3 gap-4 mt-4">
        <div class="col-span-1">
            @livewire('dashboard.clients', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
        <div class="col-span-1">
            @livewire('dashboard.request-by-method', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
        <div class="col-span-1">
            @livewire('dashboard.error_response_by_code', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
        </div>
    </div>

    {{-- Incident feed --}}
    <div class="mt-4">
        @livewire('security.incident.feed', ['start_time' => $start_time, 'end_time' => $end_time, 'domain' => $domain])
    </div>

</x-app-layout>
