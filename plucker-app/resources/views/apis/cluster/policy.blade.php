<x-app-layout :data=[$domain,$start_time,$end_time]>
    <x-slot name="page">{{ __('Cluster Policies') }}</x-slot>
    <x-slot name='crumb'>
        <li>
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="/APIs" class="ms-1 text-sm font-medium text-zinc-500 hover:text-blue-600">APIs</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 w-3 h-3 text-zinc-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="ms-1 text-sm font-medium text-zinc-500">Policies</span>
            </div>
        </li>
    </x-slot>

    <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="default-table">
                <thead>
                    <tr class="border-b border-zinc-100">
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Name</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Owner</th>
                        <th class="px-5 py-2.5 text-right text-xs font-medium text-zinc-400 uppercase tracking-wide">Linked Clusters</th>
                        <th class="px-5 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Last Changed</th>
                        <th class="px-5 py-2.5 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($cluster_policy as $policy)
                    @livewire('apis.cluster.policy-view', ['cluster_policy' => $policy, 'domain' => $domain])
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-zinc-100 flex justify-end">
            <a href="{{ route('api_cluster_policy_edit', ['domain' => base64_encode($domain), 'policy' => 'new', 'start_datetime' => request()->get('start_datetime'), 'end_datetime' => request()->get('end_datetime')]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Create Policy
            </a>
        </div>
    </div>

    <script>
        if (document.getElementById("default-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            new simpleDatatables.DataTable("#default-table", { searchable: true, sortable: true });
        }
    </script>
</x-app-layout>
