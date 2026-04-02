<div class='col-span-9'>
    <div class=' flex pb-4'>



        <div class='pl-4 rounded-full bg-[#858585] text-white text-sm font-semibold overflow-x-break break-all text-left py-3 px-5'>
            {{$key}}

            @if($key == '')
            Choose cluster from the list
            @endif
        </div>
        <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers" data-dropdown-placement="bottom" class="flex" type="button">
            <div class="mt-1 ml-2  px-3 py-3 bg-gray-100 text-white text-sm rounded-full mr-2 border border-gray-400 hover:bg-blue-400">
                <svg width="12" height="12" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L7 7L13 1" stroke="#454545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </button>
    </div>

    <!-- Dropdown menu -->
    <div id="dropdownUsers" class="z-10 hidden bg-white shadow-xl rounded-xl dark:bg-gray-700">
        <ul class="max-h-64 max-w-sm overflow-auto py-2  text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUsersButton">
            @foreach($list as $res)
            <li class="pt-3 pb-3 pl-1 dark:hover:bg-gray-600 dark:hover:text-white hover:bg-blue-400 text-sm">
                <a href="{{route('clusters',['domain'=>base64_encode($domain)])}}?start_datetime={{$start_time}}&end_datetime={{$end_time}}&cluster_id={{base64_encode(trim($res->id))}}" class='font-semibold py-2 px-4'>{{$res->name}}</a>
            </li>
            @endforeach
        </ul>

    </div>


</div>