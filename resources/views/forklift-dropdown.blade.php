<div
    class="relative inline-block text-left"
    x-data="{
        showNavigationDropdown: false,

        // move assets
        locations: @entangle('locations'),
    }"
>
    <div class="flex items-center justify-end ">
        <div class="flex h-9 w-64 border border-black focus:outline-none bg-black">
            <div class="-translate-y-1 -translate-x-1 w-full grid grid-cols-1 self-stretch text-base text-black ring-1 ring-black ring-offset-0 ring-offset-transparent transform transition-transform bg-white transform active:-translate-y-0 active:-translate-x-0">
                <button
                    @click="showNavigationDropdown = ! showNavigationDropdown"
                    type="button"
                    class="inline-flex w-full items-center justify-center"
                    id="menu-button"
                    aria-expanded="true"
                    aria-haspopup="true"
                >
                    <div class="w-4/5">Move Asset</div>

                    <div class="inline-flex w-1/5 items-center justify-center">
                        <!-- Heroicon name: solid/chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#4b5563" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        x-show="showNavigationDropdown"
        class="origin-top-right absolute right-0 mt-2 w-56 border border-black shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="menu-button"
        tabindex="-1"
        @click.outside="showNavigationDropdown = false"
        x-cloak
    >
        <div class="py-1 flex" role="none">
            <svg wire:loading class="m-4 w-4 h-4 text-gray-500" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#6B7280">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="2">
                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"/>
                        <path d="M36 18c0-9.94-8.06-18-18-18">
                            <animateTransform
                                attributeName="transform"
                                type="rotate"
                                from="0 18 18"
                                to="360 18 18"
                                dur="1s"
                                repeatCount="indefinite"/>
                        </path>
                    </g>
                </g>
            </svg>

            @if (null !== $location)
                <button @click="$wire.changeCurrentNavigation({{ $location->parent ?? 'null' }}, 1)" class="text-gray-700 block mx-1 my-1 p-2 text-sm hover:bg-gray-200" role="menuitem" tabindex="-1" id="menu-item-0" wire:loading.remove>
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Back</span>
                </button>
            @else
                <button class="text-gray-200 block mx-1 my-1 p-2 text-sm cursor-not-allowed" role="menuitem">
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Back</span>
                </button>
            @endif

            <div class="">
                <div class="text-gray-500 text-xs mr-2">Current Location:</div>
                <div class="">{{ $location?->title ?? 'Root' }}</div>
            </div>
        </div>

        <div class="py-1 divide-y divide-slate-100" role="none">
            <template x-for="location in locations.data">
                <button @click="$wire.changeCurrentNavigation(location.id, 1)" class="text-gray-700 block px-2 py-2 text-sm hover:bg-gray-100 flex text-left w-full" role="menuitem" tabindex="-1" id="menu-item-2">
                    <!--icon/folder -->
                    <div class="flex items-center justify-start">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                            <path fill="#ffd83d" d="M38.65,18v18.4H11.91V13.57H24.16V18Z"/>
                            <path d="M38.65,37.43H11.91a1,1,0,0,1-1-1V13.57a1,1,0,0,1,1-1H24.16a1,1,0,0,1,1,1V17H38.65a1,1,0,0,1,1,1v18.4A1,1,0,0,1,38.65,37.43Zm-25.74-2H37.65V19H24.16a1,1,0,0,1-1-1V14.57H12.91Z"/>
                        </svg>

                        <span class="max-w-full" x-text="location.title"></span>
                    </div>
                </button>
            </template>
        </div>

        {{-- Pagination --}}
        <div class="py-1 px-1 flex justify-center" role="none">
            <nav class="relative z-0 inline-flex shadow-sm -space-x-px" aria-label="Pagination">

                <template x-if="locations.current_page > 1">
                    <div class="flex gap-0">
                        <button @click="$wire.changeCurrentNavigation({{ $location?->id ?? 'null' }}, 1)" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-200">
                            <span class="sr-only">First Page</span>
                            <!-- Heroicon name: solid/chevron-double-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button @click="$wire.changeCurrentNavigation({{ $location?->id ?? 'null' }}, locations.current_page - 1)" class="bg-white border border-black text-gray-500 hover:bg-gray-200 relative inline-flex items-center px-4 py-2 text-sm font-medium">
                            <span class="sr-only">Previous</span>
                            <!-- Heroicon name: solid/chevron-left -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </template>

                <template x-if="locations.current_page === 1">
                    <div class="flex gap-0">
                        <button class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-200 cursor-not-allowed">
                            <span class="sr-only">First Page</span>
                            <!-- Heroicon name: solid/chevron-double-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="text-gray-200 cursor-not-allowed relative inline-flex items-center px-4 py-2 text-sm font-medium">
                            <span class="sr-only">Previous</span>
                            <!-- Heroicon name: solid/chevron-left -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </template>

                <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                <button @click="$wire.changeCurrentNavigation({{ $location?->id ?? 'null' }}, locations.current_page)" class="bg-white border border-black text-gray-500 hover:bg-gray-200 relative inline-flex items-center px-4 py-2 text-sm font-medium" x-text="locations.current_page"></button>

                <template x-if="locations.current_page !== locations.last_page">
                    <div class="flex gap-0">
                        <button @click="$wire.changeCurrentNavigation({{ $location?->id ?? 'null' }}, locations.current_page + 1)" class="bg-white border border-black text-gray-500 hover:bg-gray-200 relative inline-flex items-center px-4 py-2 text-sm font-medium">
                            <span class="sr-only">Next</span>
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button @click="$wire.changeCurrentNavigation({{ $location?->id ?? 'null' }}, locations.last_page)" class="relative inline-flex items-center px-2 py-2 bg-white text-sm font-medium text-gray-500 hover:bg-gray-200">
                            <span class="sr-only">Last Page</span>
                            <!-- Heroicon name: solid/chevron-double-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </template>

                <template x-if="locations.current_page === locations.last_page">
                    <div class="flex gap-0">
                        <button class="text-gray-200 cursor-not-allowed relative inline-flex items-center px-4 py-2 text-sm font-medium">
                            <span class="sr-only">Next</span>
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-200 cursor-not-allowed">
                            <span class="sr-only">Last Page</span>
                            <!-- Heroicon name: solid/chevron-double-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </template>
            </nav>
        </div>

        <div class="py-1 bg-primay-green hover:underline w-full" role="none">
            <button @click="$wire.moveAsset()" class="text-gray-700 text-center  block px-4 py-2 text-sm w-full" role="menuitem" tabindex="-1" id="menu-item-6">Move Here</button>
        </div>
    </div>
</div>
