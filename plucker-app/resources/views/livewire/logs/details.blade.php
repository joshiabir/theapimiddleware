<div class="w-full">
    @if(isset($log) && $dlp == 'false')
        @foreach($log as $lo)
        <div id="{{ base64_encode($lo->created_at) }}" class="border-t border-b border-zinc-100 bg-zinc-50">
            <div class="p-5 grid grid-cols-2 gap-4 max-w-full">

                {{-- Request Headers --}}
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Request Headers</p>
                    </div>
                    <div class="p-4 overflow-auto max-h-48">
                        @php $req_headers = json_decode($lo->request_headers, true); @endphp
                        @if ($req_headers !== null)
                            <table class="w-full text-xs font-mono">
                                @foreach($req_headers as $key => $value)
                                <tr class="border-b border-zinc-50 last:border-0">
                                    <td class="py-1 pr-4 text-zinc-400 whitespace-nowrap align-top">{{ $key }}</td>
                                    <td class="py-1 text-zinc-700 break-all">{{ is_array($value) ? $value[0] : $value }}</td>
                                </tr>
                                @endforeach
                            </table>
                        @else
                            <p class="text-xs text-zinc-400">No headers</p>
                        @endif
                    </div>
                </div>

                {{-- Response Headers --}}
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Response Headers</p>
                    </div>
                    <div class="p-4 overflow-auto max-h-48">
                        @php
                            $res_headers = json_decode($lo->response_headers, true);
                        @endphp
                        @if ($res_headers !== null)
                            <table class="w-full text-xs font-mono">
                                @foreach($res_headers as $key => $value)
                                <tr class="border-b border-zinc-50 last:border-0">
                                    <td class="py-1 pr-4 text-zinc-400 whitespace-nowrap align-top">{{ $key }}</td>
                                    <td class="py-1 text-zinc-700 break-all">{{ $value }}</td>
                                </tr>
                                @endforeach
                            </table>
                        @elseif($lo->response_status == null)
                            <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap">{{ $lo->response_headers }}</pre>
                        @else
                            <p class="text-xs text-zinc-400">No headers</p>
                        @endif
                    </div>
                </div>

                {{-- Request Parameters --}}
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Request Parameters</p>
                    </div>
                    <div class="p-4 overflow-auto max-h-48">
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->prams), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>

                {{-- Request Body --}}
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Request Body</p>
                    </div>
                    <div class="p-4 overflow-auto max-h-48">
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->request_body), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>

                {{-- Response Body (full width) --}}
                <div class="col-span-2 bg-white border border-zinc-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Response Body</p>
                    </div>
                    <div class="p-4 overflow-auto max-h-64">
                        @if(json_validate($lo->response_body))
                            <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ json_encode(json_decode($lo->response_body), JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono">{{ $lo->response_body }}</pre>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endforeach

    @elseif(isset($log) && $dlp == 'true')
        @foreach($log as $lo)
        <div id="{{ base64_encode($lo->created_at) }}" class="border-t border-b border-zinc-100 bg-zinc-50">
            <div class="p-5">
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden max-w-2xl">
                    <div class="px-4 py-2.5 border-b border-zinc-100 flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">DLP</span>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">Controlled Parameters</p>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-zinc-500 mb-3">
                            <span class="font-medium text-zinc-700">{{ $lo->count }}</span> item(s) redacted
                        </p>
                        <pre class="text-xs text-zinc-700 break-all whitespace-pre-wrap font-mono overflow-auto max-h-64">{{ json_encode(json_decode($lo->value), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
