<div class="relative" x-data="{ open: false }">
    <!-- Tombol Shortcut -->
    <!-- Tombol View Grid dengan Tooltip -->
    <button
        @mouseover="tooltip = true"
        @mouseleave="tooltip = false"
        @click="open = !open"
        class="p-2 rounded-full hover:bg-gray-200/30 dark:bg-transparent"
        x-data="{ tooltip: false }"
    >
        <!-- Ikon Grid -->
        <x-heroicon-o-view-columns class="w-5 h-5 text-gray-400 dark:text-gray-500 dark:hover:text-primary-600"/>

        <!-- Tooltip -->
        <div
            x-show="tooltip"
            class="absolute z-50 px-2 py-1 mt-1 text-xs text-gray-700 rounded shadow-lg top-full bgshortcut dark:text-gray-200"
            style="white-space: nowrap; left: 50%; transform: translateX(-50%);"
        >
            Shortcut
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div
        x-show="open"
        @click.away="open = false"
        class="cta-modal fixed mt-3 lg:right-10 min-w-[22vh] min-h-[25vh] rounded-md p-[0.1rem] bgshortcut lg:translate-x-[-2rem] translate-x-[-1rem] right-0 z-99"
    >
        <div class="items-center py-4 leading-4 text-center text-gray-600 rounded-md dark:text-gray-300">
            <h3 class="font-medium text-md">Shortcut menu</h3>
{{--            <span class="text-xs font-normal">ini adalah shortcut menu</span>--}}
        </div>
        <div class="grid items-center grid-cols-2 p-[0.5rem] mt-2 overflow-hidden rounded-md ">
            @foreach ($shortcuts as $shortcut)
                <a wire:navigate
                   href="{{ $shortcut['url'] }}"
                   target="_self"
                    class="z-10 flex flex-col items-center py-2 space-y-2 text-center transition duration-300 ease-in-out transform rounded-md cursor-pointer hover:bg-gray-100 dark:hover:bg-primary-600/20 hover:scale-105"
                >
                    <x-dynamic-component :component="$shortcut['icon']" class="w-5 h-5 text-gray-600 transition duration-300 dark:text-gray-200 hover:text-primary-600" />
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ $shortcut['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
