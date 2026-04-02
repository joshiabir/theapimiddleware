<div class='col-span-3'>
    <div>
        <h3 class='p-3 text-xl font-semibold'>Users</h3>
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id='client-table'>
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 ">
                <tr class='rounded-full'>
                    <th scope="col" class="px-6 py-3 rounded-l-full bg-gray-200">
                        User Address
                    </th>
                    <th scope="col" class="px-6 py-3 rounded-r-full bg-gray-200">
                        Hits
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr class="wb bg-gray-50 wb border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white hover:bg-blue-400 rounded-full">
                        <a href="{{route('logs',['domain'=>base64_encode($domain)])}}?end_datetime={{$end_time}}&start_datetime={{$start_time}}&user={{preg_replace('/[\[\]\'"]/', '', $client->client)}}" class='font-semibold py-2 px-4'>{{preg_replace('/[\[\]\'"]/', '', $client->client)}}</a>
                    </th>
                    <td class="px-6 py-4">
                        {{$client->count}}
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <script>
        if (document.getElementById("client-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#client-table", {
                searchable: false,
                sortable: false,
                perPage:5,
                perPageSelect:false,
            });
        }
    </script>

</div>