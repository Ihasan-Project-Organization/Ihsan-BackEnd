<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'إحسان') }}</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <div class="min-h-screen">@include('layouts.navigation')
        @isset($header)
            <header class="border-b border-[#dfe6d5] bg-white">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">{{ $header }}</div>
            </header>
        @endisset
        <main>{{ $slot }}</main>
    </div>
</body>

</html>
