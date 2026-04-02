<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">
        {{ __('Clusters') }}
    </x-slot>
    <x-slot name='crumb'>
    </x-slot>
    <div class="flex pb-24">
        <div class="w-full sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-9 gap-3">
            @livewire('apis.cluster.logs.simple-list',['cluster_id'=>base64_decode($cluster_id),'domain'=>$domain,'start_time'=>$start_time, 'end_time'=>$end_time])
            @livewire('apis.cluster.logs.total_requests',['cluster_id'=>base64_decode($cluster_id),'start_time'=>$start_time, 'end_time'=>$end_time,'domain'=>$domain])
            @livewire('apis.cluster.logs.response_code',['cluster_id'=>base64_decode($cluster_id),'start_time'=>$start_time, 'end_time'=>$end_time,'domain'=>$domain])
            @livewire('apis.cluster.logs.methods',['cluster_id'=>base64_decode($cluster_id),'start_time'=>$start_time, 'end_time'=>$end_time,'domain'=>$domain])
            @livewire('apis.cluster.logs.clients',['cluster_id'=>base64_decode($cluster_id),'start_time'=>$start_time, 'end_time'=>$end_time,'domain'=>$domain])
        </div>

    </div>
    </div>



</x-app-layout>