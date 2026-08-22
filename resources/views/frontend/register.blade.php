<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="اختر نوع حسابك في منصة إحسان">
    <title>إنشاء حساب | إحسان</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen w-full items-center justify-center px-3 py-4 sm:px-6 sm:py-8">
        <section class="w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-xl sm:rounded-3xl sm:shadow-2xl">
            <header class="bg-[#31421e] px-4 py-7 text-center text-white sm:px-10 sm:py-12">
                <p class="text-sm font-bold text-[#cdd9bd]">منصة إحسان</p>
                <h1 class="mt-2 text-2xl font-extrabold leading-[1.7] sm:text-4xl">إنشاء حساب جديد</h1>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-[#e6ecde] sm:text-base">اختر نوع الحساب المناسب
                    لك للانتقال إلى نموذج التسجيل.</p>
            </header>

            <div class="grid gap-4 p-4 sm:gap-5 sm:p-8 md:grid-cols-2 lg:p-12">
                <a href="{{ route('frontend.elderly.register') }}"
                    class="group overflow-hidden rounded-2xl border-2 border-slate-200 bg-white transition hover:-translate-y-1 hover:border-[#718256] hover:shadow-xl">
                    <img src="{{ asset('assets/img/oldage.jpeg') }}" alt="تسجيل كبير السن"
                        class="block h-36 w-full object-cover sm:h-44 lg:h-52">
                    <div class="p-5 text-center sm:p-6">
                        <h2 class="text-xl font-extrabold leading-[1.7] text-[#31421e] sm:text-2xl">كبير السن</h2>
                        <p class="mt-2 text-sm leading-7 text-slate-500">أنشئ حسابًا لطلب الخدمات والمساعدة من
                            المتطوعين.</p>
                        <span
                            class="mt-5 inline-flex min-h-12 items-center justify-center rounded-xl bg-[#31421e] px-6 py-3 font-bold text-white transition group-hover:bg-[#52643a]">بدء
                            التسجيل</span>
                    </div>
                </a>

                <a href="{{ route('frontend.volunteer.register') }}"
                    class="group overflow-hidden rounded-2xl border-2 border-slate-200 bg-white transition hover:-translate-y-1 hover:border-[#718256] hover:shadow-xl">
                    <img src="{{ asset('assets/img/vol.jpeg') }}" alt="تسجيل المتطوع"
                        class="block h-36 w-full object-cover sm:h-44 lg:h-52">
                    <div class="p-5 text-center sm:p-6">
                        <h2 class="text-xl font-extrabold leading-[1.7] text-[#31421e] sm:text-2xl">متطوع</h2>
                        <p class="mt-2 text-sm leading-7 text-slate-500">أنشئ حسابًا للمشاركة في تقديم الخدمات لكبار
                            السن.</p>
                        <span
                            class="mt-5 inline-flex min-h-12 items-center justify-center rounded-xl bg-[#31421e] px-6 py-3 font-bold text-white transition group-hover:bg-[#52643a]">بدء
                            التسجيل</span>
                    </div>
                </a>
            </div>

            <footer class="border-t border-slate-200 px-4 py-5 text-center text-xs leading-7 text-slate-600 sm:px-5 sm:py-6 sm:text-sm">
                لديك حساب بالفعل؟
                <a href="{{ route('frontend.login') }}" class="font-extrabold text-[#52643a] hover:underline">تسجيل
                    الدخول</a>
            </footer>
        </section>
    </main>
</body>

</html>
