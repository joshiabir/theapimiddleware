<div>
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" wire:click='manage' value="" {!!$warning!!} class="sr-only peer" {{$checked}}>
        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
    </label>
</div>
