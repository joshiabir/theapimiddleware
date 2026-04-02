<div class="p-4 rounded-2xl bg-[#858585] dark:bg-gray-800 col-span-6">
    @foreach($methods as $method)
    <span class="bg-blue-600 text-white text-xs font-medium me-1 px-2.5 py-1 rounded-full">
        <a href="{{route('logs',['domain'=>base64_encode($domain)])}}?method={{$method->method}}&url={{parse_url($key)['path']}}&start_datetime={{$start_time}}&end_datetime={{$end_time}}">{{$method->method}}
        </a>
    </span>
    @endforeach
    <h4 class="text-2xl break-all pt-4 pb-2 text-white">{{$key}}</h4>
    @if($last_connection != '[]')
    <p class='text-xs font-semibold text-white'>Last connected {{$last_connection}}</p>
    @else
    <p class='text-xs font-semibold text-white'>No requests has been made yet</p>
    @endif
</div>