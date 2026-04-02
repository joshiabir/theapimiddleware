<div>
    <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" class="relative inline-flex items-center text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 p-2 bg-gray-200 rounded-full" type="button">
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20">
            <path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
        </svg>
        @if(count($notifications)>0)
        <span class="sr-only">Notifications</span>
        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900">{{count($notifications)}}</div>
    </button>

    @endif
    </button>
    <div id="dropdownNotification" class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-800 dark:divide-gray-700" aria-labelledby="dropdownNotificationButton">
        <div class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-800 dark:text-white">
            Notifications
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @if(count($notifications)>0)
            @foreach($notifications as $notification)
            <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">

                <div class="w-full ps-3">
                    <div>
                        <h3 class='text-sm font-bold text-black'>{{$notification['heading']}}</h3>
                    </div>
                    <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">{{$notification['message']}}</div>
                </div>
            </a>
            @endforeach
            @else
            <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">

                <div class="w-full ps-3">

                    <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">No Notifications</div>
                </div>
            </a>
            @endif

        </div>
    </div>
</div>