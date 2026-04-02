<div>
    <tr class="hover:bg-zinc-50 transition-colors">
        <td class="px-5 py-3">
            @livewire('apis.cluster.edit.policy', ['policy_id' => $cluster_policy->id, 'status' => $cluster_policy->status, 'name' => $cluster_policy->name, 'linked_cluster' => $linked_clusters[0]->count])
        </td>
        <td class="px-5 py-3 font-medium text-zinc-800 text-sm">{{ $cluster_policy->name }}</td>
        <td class="px-5 py-3 text-sm text-zinc-500">{{ $owner[0]->name }}</td>
        <td class="px-5 py-3 text-right text-sm text-zinc-700">{{ $linked_clusters[0]->count }}</td>
        <td class="px-5 py-3 text-xs text-zinc-400 font-mono">{{ $cluster_policy->updated_at }}</td>
        <td class="px-5 py-3 text-center">
            <a href="{{ route('api_cluster_policy_edit', ['domain' => base64_encode($domain), 'policy' => $cluster_policy->id, 'start_datetime' => request()->get('start_datetime'), 'end_datetime' => request()->get('end_datetime')]) }}"
               class="text-xs text-blue-600 hover:underline font-medium">Edit</a>
        </td>
    </tr>
</div>
