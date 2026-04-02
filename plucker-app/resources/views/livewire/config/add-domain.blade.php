<div class=''>
    <h3 class='text-xl font-semibold mb-5'>Add a new Domain</h3>
    <form action="{{route('init')}}?step=1" method='POST'>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="alert alert-danger text-red-600">
            {{$error}}
        </div>
        @csrf
        <div class="mb-5">
            <label for="ip" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Application
                Domain</label>
            <input type="text" name='ip' id="ip" wire.model="ip"
                class="shadow-sm ag bg-gray-50 ag border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                placeholder='example.com' required />
            <p class='text-xs mt-1 font-semibold text-gray-600'>This is the main application server where the APIs are
                processed, You can also use IP addresses.</p>
        </div>
        <div>
            <div class="mb-5">
                <label for="domain" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">Mask
                    Domain</label>
                <input type="text" name='domain_a' id="domain" wire:model='domain_a'
                    class="shadow-sm ag bg-gray-50 ag border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"
                    placeholder="api.example.com" />
                <p class='text-xs mt-1 font-semibold text-gray-600'>This is the domain which will be utilized as an
                    entry point.</p>
                <p class='p-3 bg-blue-200 text-blue-900 font-regular rounded-md text-xs mt-2 mb-2 max-w-lg'>Please make
                    sure to change your DNS and create an A record for the domain mentioned with IP Address
                    {{env('SERVER_IP')}}</p>
            </div>

            <button type="submit"
                class="mt-8 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Next</button>
        </div>
    </form>
</div>