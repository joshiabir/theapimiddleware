<div>
    <tr class="hover:bg-zinc-50 transition-colors">
        <td class="px-5 py-3">
            @livewire('apis.cluster.edit.cluster', ['cluster_id' => $cluster->id, 'status' => $cluster->status, 'name' => $cluster->name])
        </td>
        <td class="px-5 py-3 font-medium text-zinc-800 text-sm">{{ $cluster->name }}</td>
        <td class="px-5 py-3 text-sm text-zinc-500">{{ $user }}</td>
        <td class="px-5 py-3 text-sm text-zinc-500">
            @if($cluster->policy_id && $cluster->policy_id !== 'None')
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600">
                    {{ $cluster->policy_id }}
                </span>
            @else
                <span class="text-xs text-zinc-400">—</span>
            @endif
        </td>
        <td class="px-5 py-3 text-right text-sm text-zinc-700">{{ $api_count }}</td>
        <td class="px-5 py-3 text-center">
            <a href="{{ route('api_cluster_edit', ['domain' => base64_encode($domain), 'cluster' => $cluster->id, 'start_datetime' => request()->get('start_datetime'), 'end_datetime' => request()->get('end_datetime')]) }}"
               class="text-xs text-blue-600 hover:underline font-medium">Edit</a>
        </td>
    </tr>
</div>
