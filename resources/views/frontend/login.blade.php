<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="منصة إحسان لخدمة كبار السن وربطهم بالمتطوعين">
    <title>تسجيل الدخول | إحسان</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen items-center justify-center p-0 sm:p-6">
        <section class="grid min-h-screen w-full overflow-hidden bg-white shadow-2xl sm:min-h-0 sm:max-w-6xl sm:rounded-3xl lg:grid-cols-2">
            <div class="hidden min-h-[720px] bg-cover bg-center lg:block" style="background-image:linear-gradient(rgba(49,66,30,.15),rgba(49,66,30,.25)),url('{{ asset('assets/img/hero-image.jpeg') }}')" role="img" aria-label="متطوع يساعد أحد كبار السن"></div>
            <div class="flex items-center px-5 py-10 sm:px-10 lg:px-16">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-9 text-center lg:text-right">
                        <p class="mb-2 text-sm font-bold text-[#718256]">منصة إحسان</p>
                        <h1 class="text-3xl font-extrabold text-[#31421e] sm:text-4xl">مرحبًا بعودتك</h1>
                        <p class="mt-3 text-sm leading-7 text-slate-500">سجّل الدخول للوصول إلى حسابك ومتابعة خدماتك.</p>
                    </div>
                    @if (session('status'))
                        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert"><ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div><label for="email" class="mb-2 block text-sm font-bold text-slate-700">البريد الإلكتروني</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" class="w-full rounded-xl border-slate-300 px-4 py-3 text-right shadow-sm focus:border-[#718256] focus:ring-[#718256]"></div>
                        <div><label for="password" class="mb-2 block text-sm font-bold text-slate-700">كلمة المرور</label><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="أدخل كلمة المرور" class="w-full rounded-xl border-slate-300 px-4 py-3 text-right shadow-sm focus:border-[#718256] focus:ring-[#718256]"></div>
                        <div class="flex flex-wrap items-center justify-between gap-3 text-sm"><label class="flex cursor-pointer items-center gap-2 text-slate-600"><input type="checkbox" name="remember" @checked(old('remember')) class="rounded border-slate-300 text-[#31421e] focus:ring-[#718256]">تذكرني</label><a href="{{ route('password.request') }}" class="font-bold text-[#52643a] hover:underline">نسيت كلمة المرور؟</a></div>
                        <button type="submit" class="w-full rounded-xl bg-[#31421e] px-5 py-3.5 font-bold text-white shadow-lg transition hover:bg-[#52643a] focus:outline-none focus:ring-4 focus:ring-[#718256]/30">تسجيل الدخول</button>
                    </form>
                    <div class="mt-9 border-t border-slate-200 pt-7"><p class="mb-4 text-center text-sm font-bold text-slate-600">ليس لديك حساب؟ اختر نوع الحساب</p><div class="grid grid-cols-1 gap-3 sm:grid-cols-2"><a href="{{ route('frontend.elderly.register') }}" class="rounded-xl border-2 border-[#718256] px-4 py-3 text-center font-bold text-[#52643a] transition hover:bg-[#eef2e8]">حساب كبير سن</a><a href="{{ route('frontend.volunteer.register') }}" class="rounded-xl border-2 border-[#718256] px-4 py-3 text-center font-bold text-[#52643a] transition hover:bg-[#eef2e8]">حساب متطوع</a></div></div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
