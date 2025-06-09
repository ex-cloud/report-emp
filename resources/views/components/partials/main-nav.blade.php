<header class="fixed top-0 items-center w-full mx-auto bg-white/30 backdrop-blur-md border-b border-[#19140035] dark:bg-[#0a0a0a]/30 dark:border-[#3E3E3A]/20 z-40 overflow-x-clip">        
    <nav class="z-40 items-center justify-between hidden mx-auto lg:flex lg:max-w-screen-2xl" aria-label="Main" data-orientation="horizontal" dir="ltr">
        <div class="container relative flex items-center justify-between h-16 mx-auto">
            <div class="flex items-center justify-between flex-1 px-6 lg:px-0 sm:items-stretch">
                <div class="flex items-center">
                    <div class="flex items-center flex-shrink-0">
                        <a
                            href="/"
                            class="flex items-baseline "
                        >
                        <div class="relative text-[#1b1b18] dark:text-[#EDEDEC] text-[24px] font-semibold leading-3 font-sans tracking-wide">
                            K2NET
                            {{-- <span class="text-[11px] font-thin">Computer and network solution</span> --}}
                        </div>
                        <img
                            src="{{ asset('assets/213213.png') }}"
                            alt="Logo"
                            class="w-6 h-6"
                        />
                        </a>
                    </div>
                    <nav class="relative z-10 items-center justify-center flex-1 hidden h-16 pl-8 sm:space-x-4 lg:flex" aria-label="Main" data-orientation="horizontal" dir="ltr">
                        <div class="relative">
                            <ul class="flex items-center justify-center flex-1 space-x-2 list-none group">
                                {{-- @auth
                                    <a
                                        href="{{ url('/admin/dashboard') }}"
                                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="/admin/login"
                                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                                    >
                                        Log in
                                    </a>
                                        <a
                                            href="/admin/register"
                                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                            Register
                                        </a>
                                @endauth --}}
                                <a href="/" wire:navigate
                                    class="flex items-center px-2 list-none transition-all ease-in-out group hover:text-gray-900 dark:text-gray-200/70 hover:bg-gray-600/10 hover:py-2 hover:px-3 hover:rounded-md ">
                                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-sky-600 text-[14px] font-semibold font-sans leading-3">Home</span>
                                </a>
                                <a href="#" wire:navigate
                                    class="flex items-center px-2 list-none transition-all ease-in-out group hover:text-gray-900 dark:text-gray-200/70 hover:bg-gray-600/10 hover:py-2 hover:px-3 hover:rounded-md ">
                                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-sky-600 text-[14px] font-semibold font-sans leading-3">Profile</span>
                                </a>
                                <a href="#" wire:navigate
                                    class="flex items-center px-2 list-none transition-all ease-in-out group hover:text-gray-900 dark:text-gray-200/70 hover:bg-gray-600/10 hover:py-2 hover:px-3 hover:rounded-md ">
                                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-sky-600 text-[14px] font-semibold font-sans leading-3">Services</span>
                                </a>
                                <a href="#" wire:navigate
                                    class="flex items-center px-2 list-none transition-all ease-in-out group hover:text-gray-900 dark:text-gray-200/70 hover:bg-gray-600/10 hover:py-2 hover:px-3 hover:rounded-md ">
                                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-sky-600 text-[14px] font-semibold font-sans leading-3">Solutions</span>
                                </a>
                                <a href="#" wire:navigate
                                    class="flex items-center px-2 list-none transition-all ease-in-out group hover:text-gray-900 dark:text-gray-200/70 hover:bg-gray-600/10 hover:py-2 hover:px-3 hover:rounded-md ">
                                    <span class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-sky-600 text-[14px] font-semibold font-sans leading-3">Tutorial</span>
                                </a>
                                
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="flex items-center gap-2 animate-fade-in !scale-100 delay-300">
                    <ul class="flex items-center justify-center flex-1 space-x-1 list-none group">
                        @auth
                            <a
                                href="{{ url('/admin/dashboard') }}" wire:navigate
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="/admin/login" wire:navigate
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                            >
                                Log in
                            </a>
                                <a
                                    href="/admin/register" wire:navigate
                                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
