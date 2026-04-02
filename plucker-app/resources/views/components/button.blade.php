<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-blue-600  rounded-md font-semibold text-sm text-white hover:bg-blue-700 active:bg-blue-600 disabled:opacity-50  transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
