<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="اختر نوع حسابك في منصة أنيس">
    <title>إنشاء حساب | أنيس</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen w-full items-center justify-center px-4 py-6 sm:px-6 sm:py-8">
        <section class="w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-xl sm:rounded-3xl sm:shadow-2xl">
            <header class="relative bg-[#31421e] px-4 py-6 text-center text-white sm:px-8 sm:py-7">
                <a href="{{ url('/') }}" class="absolute right-4 top-4 inline-flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-xs text-white/90 transition hover:bg-white/20">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    <span>الرئيسية</span>
                </a>
                <img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس" class="mx-auto h-16 w-24 rounded-xl bg-[#fffdf9] object-contain px-2 py-1">
                <h1 class="mt-1 text-xl font-extrabold leading-snug sm:text-2xl">إنشاء حساب جديد</h1>
                <p class="mx-auto mt-1.5 max-w-md text-xs leading-6 text-[#e6ecde] sm:text-sm">اختر نوع الحساب المناسب لك للانتقال إلى نموذج التسجيل.</p>
            </header>

            <div class="grid gap-4 p-5 sm:gap-5 sm:p-7 md:grid-cols-2">
                <a href="{{ route('frontend.elderly.register') }}"
                    class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-[#718256] hover:shadow-lg">
                    <img src="{{ asset('assets/img/assistance-seeker-register.jpg') }}" alt="تسجيل طالب مساعدة من كبار السن أو ذوي الإعاقة أو مبتوري الأطراف"
                        class="block h-44 w-full object-cover object-center sm:h-48">
                    <div class="p-4 text-center sm:p-5">
                        <h2 class="text-lg font-extrabold text-[#31421e]">طالب مساعدة</h2>
                        <p class="mt-1 text-xs leading-5 text-slate-500">لكبار السن وذوي الإعاقة ومبتوري الأطراف وكل من يحتاج إلى المساندة.</p>
                        <span class="mt-4 inline-flex min-h-10 items-center justify-center rounded-xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white transition group-hover:bg-[#52643a] sm:text-sm">
                            بدء التسجيل
                        </span>
                    </div>
                </a>

                <a href="{{ route('frontend.volunteer.register') }}"
                    class="group overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-[#718256] hover:shadow-lg">
                    <img src="{{ asset('assets/img/vol.jpeg') }}" alt="تسجيل المتطوع"
                        class="block h-32 w-full object-cover sm:h-36">
                    <div class="p-4 text-center sm:p-5">
                        <h2 class="text-lg font-extrabold text-[#31421e]">متطوع</h2>
                        <p class="mt-1 text-xs leading-5 text-slate-500">أنشئ حسابًا للمشاركة في تقديم الخدمات والمساندة لطالبي المساعدة.</p>
                        <span class="mt-4 inline-flex min-h-10 items-center justify-center rounded-xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white transition group-hover:bg-[#52643a] sm:text-sm">
                            بدء التسجيل
                        </span>
                    </div>
                </a>
            </div>

            <footer class="border-t border-slate-200 px-4 py-4 text-center text-xs text-slate-600 sm:text-sm">
                لديك حساب بالفعل؟
                <a href="{{ route('frontend.login') }}" class="font-extrabold text-[#52643a] hover:underline">تسجيل الدخول</a>
            </footer>
        </section>
    </main>
</body>

</html>
