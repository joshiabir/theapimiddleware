<div class="col-span-6 grid grid-cols-1 md:grid-cols-6 gap-3">
    @if($none_req === FALSE)
    <div class="p-4 bg-[#E5E5E5] rounded-xl col-span-3 ">
        <div class="mt-4 overflow-y-auto max-h-[250px]">
            <h3 class="text-lg font-semibold mb-4 ">Request Parameters</h3>
            <pre class="text-x p-2 text-gray-600 rounded">@foreach($request_keys as $r){{json_encode(json_decode($r->keys), JSON_PRETTY_PRINT)}}@endforeach</pre>
        </div>
    </div>
    @else
    <div class="p-4 bg-gray-200 rounded-xl col-span-3">
        <p class="text-sm">There have been no request prams yet for this Endpoint</p>
    </div>
    @endif
    @if($none_res === FALSE)
    <div class="p-4 bg-[#E5E5E5] rounded-xl col-span-3 ">
        <div class="mt-4 overflow-y-auto max-h-[250px]">
            <h3 class="text-lg font-semibold mb-4 ">Response Parameters</h3>
            <pre class="text-xs text-gray-600 p-2 rounded">@foreach($response_keys as $r){{json_encode(json_decode($r->keys), JSON_PRETTY_PRINT)}}@endforeach</pre>
        </div>
    </div>
    @else
    <div class="p-4 bg-gray-200 rounded-xl col-span-3">
        <p class="text-sm">There have been no logs yet for this Endpoint</p>
    </div>
    @endif

</div>