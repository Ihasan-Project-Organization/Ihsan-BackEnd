<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-[100dvh] flex-col items-center justify-center bg-[#f3eee5] px-4 py-6 sm:px-6">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="guest-panel mt-6 w-full max-w-md overflow-hidden rounded-2xl bg-white px-5 py-6 shadow-lg sm:px-7 sm:py-7">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
