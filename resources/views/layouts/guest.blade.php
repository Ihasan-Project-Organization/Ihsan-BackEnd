<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'إحسان') }}</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen w-full items-center justify-center px-3 py-6 sm:px-4 sm:py-8">
        <div class="w-full max-w-lg">
            <a href="/" class="mb-6 block text-center text-3xl font-black text-[#31421e]">إحسان</a>
            <section class="rounded-2xl border border-[#dfe6d5] bg-white p-4 shadow-xl sm:rounded-3xl sm:p-9">{{ $slot }}
            </section>
            <p class="mt-5 text-center text-xs text-slate-400">العطاء أقرب، والرعاية أسهل.</p>
        </div>
    </main>
</body>

</html>
