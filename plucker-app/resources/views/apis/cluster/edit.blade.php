<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">{{ __('Edit Cluster') }}</x-slot>
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
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ms-1 text-sm font-medium text-zinc-500">{{ $cluster[0]->name }}</span>
            </div>
        </li>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        {{-- Cluster details form --}}
        @livewire('apis.cluster.edit.head', [
            'name'        => $cluster[0]->name,
            'policy_id'   => $cluster[0]->policy_id,
            'description' => $cluster[0]->description,
            'cluster_id'  => $cluster[0]->id,
            'domain'      => $domain
        ])

        @if($cluster[0]->id != 'new')
        {{-- API list --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">APIs in this Cluster</p>
            </div>
            <div class="overflow-x-auto overflow-y-auto max-h-[480px]">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-white z-10">
                        <tr class="border-b border-zinc-100">
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Endpoint</th>
                            <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Hits</th>
                            <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Unique Users</th>
                            <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Success Rate</th>
                            <th class="px-5 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @foreach($apis as $api)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-5 py-3">
                                @livewire('apis.cluster.edit.api_table', ['api_id' => $api->api_id, 'status' => $api->status, 'url' => $api->url, 'cluster_id' => $cluster[0]->id])
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('apis', ['domain' => base64_encode($domain)]) }}?end_datetime={{ $end_time }}&start_datetime={{ $start_time }}&api={{ base64_encode($api->url) }}"
                                   class="text-xs font-mono text-zinc-700 hover:text-blue-600">{{ $api->url }}</a>
                            </td>
                            <td class="px-5 py-3 text-right text-sm text-zinc-700">{{ $api->hits }}</td>
                            <td class="px-5 py-3 text-right text-sm text-zinc-700">{{ $api->clients }}</td>
                            <td class="px-5 py-3 text-right text-sm">
                                @if($api->hits > 0)
                                    @php $rate = number_format(($api->hits - $api->failed) / $api->hits * 100, 0); @endphp
                                    <span class="{{ $rate >= 95 ? 'text-green-600' : ($rate >= 80 ? 'text-amber-600' : 'text-red-600') }} font-medium">
                                        {{ $rate }}%
                                    </span>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @livewire('apis.cluster.edit.remove_api', ['api_id' => $api->api_id, 'status' => $api->status, 'url' => $api->url, 'end_date' => $end_time, 'start_date' => $start_time, 'domain' => $domain, 'cluster_id' => $cluster[0]->id])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-zinc-100 flex justify-end">
                <button data-modal-target="default-modal" data-modal-toggle="default-modal" type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add APIs
                </button>
            </div>
        </div>

        @livewire('apis.cluster.edit.add_api', [
            'api_array_o' => $free_apis_old,
            'api_array_f' => $free_apis_new,
            'end_date'    => $end_time,
            'start_date'  => $start_time,
            'domain'      => $domain,
            'cluster_id'  => $cluster[0]->id
        ])

        <script>
            if (document.getElementById("default-table_1") && typeof simpleDatatables.DataTable !== 'undefined') {
                new simpleDatatables.DataTable("#default-table_1", { searchable: true, perPage: 5, sortable: true });
            }
        </script>
        @endif
    </div>
</x-app-layout>
