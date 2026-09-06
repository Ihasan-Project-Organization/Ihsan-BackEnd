<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الحساب قيد المراجعة | منصة إحسان</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3eee5] text-slate-800 antialiased flex flex-col justify-between">
    <!-- رأس الصفحة البسيط -->
    <header class="w-full bg-white/80 backdrop-blur-md border-b border-slate-200/80 py-4 px-6 sm:px-12 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🌱</span>
            <span class="text-lg font-black text-[#31421e]">منصة إحسان</span>
        </div>
        <div>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-red-700 transition">
                        تسجيل الخروج
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-bold text-[#31421e] hover:text-[#52643a] transition">
                    تسجيل الدخول
                </a>
            @endauth
        </div>
    </header>

    <!-- المحتوى الرئيسي -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden text-center p-6 sm:p-10">
            <!-- أيقونة الحالة -->
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-amber-50 text-amber-600 mb-6 border border-amber-100 shadow-inner">
                <svg class="h-10 w-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- شارة الحالة -->
            <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 border border-amber-200 px-4 py-1 text-xs font-extrabold text-amber-800 mb-4">
                <span class="h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                <span>الحساب قيد التدقيق والمراجعة</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-[#31421e] mb-3">
                حسابك قيد المراجعة
            </h1>

            <p class="text-sm leading-relaxed text-slate-600 mb-6">
                شكراً لانضمامك إلى <strong class="text-[#31421e]">منصة إحسان</strong>. يجري حالياً مراجعة وتدقيق بياناتك ووثائقك من قِبل إدارة المنصة للتحقق والتأكد من مطابقة المعايير المعتمدة لسلامة وأمان كبار السن.
            </p>

            @auth
                <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-200/80 text-right space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-bold">صاحب الحساب:</span>
                        <span class="text-slate-800 font-black">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-bold">البريد الإلكتروني:</span>
                        <span class="text-slate-800 font-mono" dir="ltr">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-bold">حالة الاعتماد:</span>
                        <span class="text-amber-700 font-bold">قيد الانتظار (Pending)</span>
                    </div>
                </div>
            @endauth

            <div class="rounded-2xl bg-[#eef2e8] p-4 text-xs leading-6 text-[#31421e] text-right mb-6">
                <p class="font-bold mb-1">📌 ماذا يحدث الآن؟</p>
                <ul class="list-disc list-inside space-y-0.5 text-slate-700 text-[11px]">
                    <li>يقوم فريق المراجعة بمطابقة الهوية والوثائق المدخلة.</li>
                    <li>عند الانتهاء من التدقيق، سيتم اعتماد الحساب وفتح كافة خصائص المنصة تلقائياً.</li>
                    <li>يمكنك العودة وتسجيل الدخول لاحقاً للتحقق من حالة حسابك.</li>
                </ul>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto rounded-xl bg-[#31421e] px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                            تسجيل الخروج والانتظار
                        </button>
                    </form>
                @else
                    <a href="{{ route('frontend.login') }}"
                        class="w-full sm:w-auto rounded-xl bg-[#31421e] px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition inline-block">
                        تسجيل الدخول
                    </a>
                @endauth
                <a href="/"
                    class="w-full sm:w-auto rounded-xl border border-slate-300 px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100 transition inline-block">
                    الصفحة الرئيسية
                </a>
            </div>
        </div>
    </main>

    <!-- تذييل الصفحة -->
    <footer class="py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} منصة إحسان لرعاية كبار السن. جميع الحقوق محفوظة.
    </footer>
</body>
</html>
