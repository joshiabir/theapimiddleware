<div class='col-span-3'>
    <div class=''>
        <h5 class='p-3 text-xl font-semibold'>Top APIs</h5>
    </div>
    <div class="w-full wb bg-gray-50 wb rounded-xl ">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 ">
                <tr>
                    <th scope="col" class="px-6 py-3 rounded-l-full">
                        Url
                    </th>
                    <th scope="col" class="px-6 py-3 rounded-r-full">
                        Hits
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800">
                @foreach($api_data as $apis)
                <tr class="wb bg-gray-50 wb border-b dark:bg-gray-800 dark:border-gray-700">

                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white hover:bg-blue-400 rounded-full">
                        <a href="{{route('apis',['domain'=>base64_encode($domain)])}}?api={{base64_encode(parse_url($apis->url)['path'])}}&end_datetime={{$end_time}}&start_datetime={{$start_time}}"> {{ parse_url($apis->url)['path']}}</a>
                    </th>
                    <td class="px-6 py-4">
                        {{$apis->count}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
