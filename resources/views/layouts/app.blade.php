<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @fluxAppearance
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] w-full text-[#1b1b18] dark:text-white scroll-smooth overflow-auto">
        <x-partials.main-nav />
        <div class="items-center w-full min-h-screen mx-auto ">
            {{ $slot }}
        </div>
        <footer class="mt-4 text-center bg-light text-lg-start">
            <div class="p-3 text-center" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2023 Company Name
            </div>
        </footer>
        @livewireScripts
        @fluxScripts
    </body>
</html>
