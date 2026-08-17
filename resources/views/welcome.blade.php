<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="إحسان منصة إنسانية تربط كبار السن بمتطوعين موثوقين لتقديم الرعاية والمساندة اليومية.">
    <meta name="theme-color" content="#173c32">
    <title>إحسان | لأن العطاء حياة</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-page overflow-x-hidden bg-[#f9faf5] text-[#173c32] antialiased">
    <header class="absolute inset-x-0 top-0 z-50">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 sm:px-8 lg:px-10" aria-label="التنقل الرئيسي">
            <a href="{{ url('/') }}" class="group flex items-center gap-3" aria-label="العودة إلى الرئيسية">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-[#1d5a48] text-white shadow-lg shadow-[#1d5a48]/20 transition group-hover:-rotate-6">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 20.5S4 16 4 9.5A4.5 4.5 0 0 1 12 6.7a4.5 4.5 0 0 1 8 2.8c0 6.5-8 11-8 11Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 6.7V15m-3-3h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span><strong class="block text-2xl font-black leading-[1.3] text-[#173c32]">إحسان</strong><small class="mt-1 block text-[10px] font-bold tracking-wide text-[#658176]">العطاء أقرب</small></span>
            </a>
            <div class="hidden items-center gap-8 text-sm font-bold text-[#49675d] lg:flex">
                <a href="#about" class="transition hover:text-[#1d5a48]">عن إحسان</a>
                <a href="#services" class="transition hover:text-[#1d5a48]">خدماتنا</a>
                <a href="#how-it-works" class="transition hover:text-[#1d5a48]">كيف نعمل؟</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-[#173c32] px-5 py-3 text-sm font-extrabold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#1d5a48]">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-full px-4 py-3 text-sm font-extrabold text-[#173c32] transition hover:bg-white/70 sm:inline-flex">دخول</a>
                    <a href="{{ route('register.choose') }}" class="rounded-full bg-[#173c32] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-[#173c32]/20 transition hover:-translate-y-0.5 hover:bg-[#1d5a48] sm:px-6">انضم إلينا</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-grid relative isolate min-h-[760px] overflow-hidden bg-[#f1f4e9] pt-28 sm:pt-32">
            <div class="hero-orb hero-orb-one" aria-hidden="true"></div>
            <div class="hero-orb hero-orb-two" aria-hidden="true"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 pb-20 pt-10 sm:px-8 sm:pt-16 lg:grid-cols-[1.05fr_.95fr] lg:px-10 lg:pb-28 lg:pt-20">
                <div class="relative z-10" data-reveal>
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#1d5a48]/10 bg-white/75 px-4 py-2 text-xs font-extrabold text-[#1d5a48] shadow-sm backdrop-blur sm:text-sm">
                        <span class="relative flex h-2.5 w-2.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#d99157] opacity-70"></span><span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#d99157]"></span></span>
                        مجتمع آمن للرعاية والتطوع
                    </div>
                    <h1 class="display-title mt-7 py-2 font-black text-[#173c32]">
                        لأنّ الاهتمام
                        <span class="relative mt-1 block w-fit text-[#c16f37]">يصنع فرقًا<svg class="absolute -bottom-2 right-0 h-3 w-full text-[#e3b38e]" viewBox="0 0 300 12" preserveAspectRatio="none" aria-hidden="true"><path d="M3 9C72 2 196 2 297 6" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg></span>
                    </h1>
                    <p class="mt-8 max-w-2xl text-lg leading-9 text-[#587067] sm:text-xl">نقرّب كبار السن من متطوعين موثوقين، لنحوّل الاحتياج اليومي إلى لحظات دافئة من الصحبة والمساندة والاطمئنان.</p>
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('frontend.elderly.register') }}" class="group inline-flex items-center justify-center gap-3 rounded-full bg-[#1d5a48] px-7 py-4 font-extrabold text-white shadow-xl shadow-[#1d5a48]/20 transition hover:-translate-y-1 hover:bg-[#173c32]">أحتاج إلى مساعدة<svg class="h-5 w-5 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                        <a href="{{ route('frontend.volunteer.register') }}" class="group inline-flex items-center justify-center gap-3 rounded-full border border-[#1d5a48]/20 bg-white/70 px-7 py-4 font-extrabold text-[#1d5a48] backdrop-blur transition hover:-translate-y-1 hover:border-[#1d5a48]/40 hover:bg-white">أريد أن أتطوع<span class="grid h-6 w-6 place-items-center rounded-full bg-[#f3dfcf] text-[#b8612f] transition group-hover:rotate-12">+</span></a>
                    </div>
                    <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-3 border-t border-[#173c32]/10 pt-6 text-sm font-bold text-[#587067]">
                        <span class="flex items-center gap-2"><span class="text-[#c16f37]">✓</span> تسجيل بسيط</span><span class="flex items-center gap-2"><span class="text-[#c16f37]">✓</span> خصوصية وأمان</span><span class="flex items-center gap-2"><span class="text-[#c16f37]">✓</span> متطوعون موثوقون</span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-[510px] lg:ml-0" data-reveal data-reveal-delay="180">
                    <div class="absolute -inset-5 rotate-3 rounded-[3.2rem] border border-[#1d5a48]/10 bg-white/40"></div>
                    <div class="relative overflow-hidden rounded-[2.75rem] bg-[#e7eadb] p-3 shadow-[0_35px_90px_-35px_rgba(23,60,50,.45)]">
                        <img src="{{ asset('assets/img/hero-image.jpeg') }}" alt="متطوع يساند أحد كبار السن" class="h-[510px] w-full rounded-[2.15rem] object-cover object-[center_32%] sm:h-[610px]">
                        <div class="absolute inset-x-3 bottom-3 h-48 rounded-b-[2.15rem] bg-gradient-to-t from-[#173c32]/75 to-transparent"></div>
                        <div class="absolute bottom-9 right-9 left-9 text-white"><p class="text-sm font-bold text-white/75">رسالتنا</p><p class="mt-1 text-xl font-black sm:text-2xl">رعاية تحفظ الكرامة، وصحبة تمنح الأمان.</p></div>
                    </div>
                    <div class="float-card absolute -right-3 top-16 rounded-2xl bg-white/95 p-4 shadow-xl backdrop-blur sm:-right-12 sm:p-5"><div class="flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-[#e6f0ea] text-[#1d5a48]"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 11.5a8.4 8.4 0 0 1-9 8.4 9.4 9.4 0 0 1-3.9-1L3 20l1.1-4A8.4 8.4 0 1 1 20 11.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span><strong class="block text-sm font-black text-[#173c32]">نحن بجانبك</strong><small class="text-xs font-bold text-[#6c817a]">دعم بخطوات واضحة</small></span></div></div>
                    <div class="float-card float-card-delayed absolute -bottom-5 left-1 rounded-2xl bg-[#d78a51] px-5 py-4 text-white shadow-xl sm:-left-10"><strong class="block text-2xl font-black">معًا</strong><span class="text-xs font-bold text-white/85">الأثر يبدأ بخطوة</span></div>
                </div>
            </div>
            <a href="#about" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-[11px] font-bold text-[#6c817a] lg:flex" aria-label="انتقل إلى القسم التالي">اكتشف أكثر<span class="scroll-indicator"><i></i></span></a>
        </section>

        <section id="about" class="relative py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="grid items-end gap-8 lg:grid-cols-[.8fr_1.2fr]">
                    <div class="min-w-0" data-reveal><span class="section-kicker">لماذا إحسان؟</span><h2 class="section-title mt-4 py-2 font-black">رعاية إنسانية <span class="text-[#c16f37]">بمعنى أعمق</span></h2></div>
                    <p class="max-w-2xl text-lg leading-9 text-[#647a72] lg:mr-auto" data-reveal data-reveal-delay="120">نبني جسورًا من الثقة بين الأجيال. تجربة صُممت ببساطة واهتمام، لتجعل طلب المساندة أو تقديمها أكثر راحة ووضوحًا.</p>
                </div>
                <div id="services" class="mt-14 grid gap-5 md:grid-cols-3">
                    <article class="service-card group" data-reveal><div class="service-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7.5-1a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM2 21v-2a6 6 0 0 1 12 0v2m1-7.5a5 5 0 0 1 7 4.5v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></div><span class="mt-7 block text-xs font-black text-[#c16f37]">01</span><h3 class="mt-2 text-2xl font-black">صحبة ومساندة</h3><p class="mt-4 leading-8 text-[#6c817a]">رفقة لطيفة ومساعدة في الاحتياجات اليومية، بما يمنح كبير السن شعورًا بالقرب والاطمئنان.</p><span class="card-arrow" aria-hidden="true">←</span></article>
                    <article class="service-card group md:translate-y-8" data-reveal data-reveal-delay="100"><div class="service-icon service-icon-warm"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></div><span class="mt-7 block text-xs font-black text-[#c16f37]">02</span><h3 class="mt-2 text-2xl font-black">عناية باحترام</h3><p class="mt-4 leading-8 text-[#6c817a]">كل تواصل يبدأ بالخصوصية وينتهي بالكرامة، ضمن تجربة تراعي راحة كبار السن وعائلاتهم.</p><span class="card-arrow" aria-hidden="true">←</span></article>
                    <article class="service-card group" data-reveal data-reveal-delay="200"><div class="service-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m9 12 2 2 4-4m5.5 2A8.5 8.5 0 1 1 3.5 12a8.5 8.5 0 0 1 17 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></div><span class="mt-7 block text-xs font-black text-[#c16f37]">03</span><h3 class="mt-2 text-2xl font-black">ثقة في كل خطوة</h3><p class="mt-4 leading-8 text-[#6c817a]">بيانات واضحة وإجراءات منظّمة تساعد على تكوين مجتمع تطوعي آمن وجدير بالثقة.</p><span class="card-arrow" aria-hidden="true">←</span></article>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="relative overflow-hidden bg-[#173c32] py-20 text-white sm:py-28">
            <div class="absolute -left-40 -top-40 h-96 w-96 rounded-full border-[70px] border-white/[.035]" aria-hidden="true"></div><div class="absolute -bottom-32 right-1/3 h-72 w-72 rounded-full bg-[#d78a51]/10 blur-3xl" aria-hidden="true"></div>
            <div class="relative mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="mx-auto max-w-2xl text-center" data-reveal><span class="section-kicker section-kicker-light">رحلة سهلة وواضحة</span><h2 class="mt-4 text-3xl font-black sm:text-5xl">ثلاث خطوات تقرّبنا</h2><p class="mt-5 text-lg leading-8 text-[#c5d3ce]">صممنا التجربة لتكون بسيطة من أول اختيار وحتى الوصول إلى حسابك.</p></div>
                <ol class="relative mt-16 grid gap-5 lg:grid-cols-3">
                    <li class="step-card" data-reveal><span class="step-number">١</span><div><h3 class="text-xl font-black">اختر دورك</h3><p class="mt-3 leading-7 text-[#b9cac4]">سجّل ككبير سن لطلب المساندة، أو كمتطوع لصناعة أثر جميل.</p></div></li>
                    <li class="step-card" data-reveal data-reveal-delay="120"><span class="step-number">٢</span><div><h3 class="text-xl font-black">أكمل بياناتك</h3><p class="mt-3 leading-7 text-[#b9cac4]">نموذج واضح ومختصر يساعدنا على تقديم تجربة مناسبة وآمنة.</p></div></li>
                    <li class="step-card" data-reveal data-reveal-delay="240"><span class="step-number">٣</span><div><h3 class="text-xl font-black">ابدأ رحلتك</h3><p class="mt-3 leading-7 text-[#b9cac4]">ادخل إلى لوحتك وتابع ملفك وخدماتك بسهولة من مكان واحد.</p></div></li>
                </ol>
            </div>
        </section>

        <section class="px-5 py-20 sm:px-8 sm:py-28">
            <div class="cta-panel relative mx-auto max-w-7xl overflow-hidden rounded-[2.5rem] bg-[#e8eee2] px-6 py-14 text-center sm:px-12 sm:py-20" data-reveal>
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full border-[42px] border-white/40" aria-hidden="true"></div><div class="absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-[#d78a51]/15 blur-2xl" aria-hidden="true"></div>
                <div class="relative min-w-0"><span class="section-kicker">مساحة صغيرة للعطاء، أثر كبير في الحياة</span><h2 class="section-title mx-auto mt-5 max-w-3xl py-2 font-black text-[#173c32]">كل يدٍ تمتد بالمساعدة، تجعل العالم أكثر دفئًا.</h2><p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-[#647a72]">سواء كنت تبحث عن المساندة أو ترغب في تقديمها، مكانك محفوظ في مجتمع إحسان.</p><a href="{{ route('register.choose') }}" class="mt-9 inline-flex items-center gap-3 rounded-full bg-[#c16f37] px-8 py-4 font-black text-white shadow-xl shadow-[#c16f37]/20 transition hover:-translate-y-1 hover:bg-[#a95729]">ابدأ الآن مجانًا<span aria-hidden="true">←</span></a></div>
            </div>
        </section>
    </main>

    <footer class="border-t border-[#173c32]/10 bg-white"><div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-5 py-8 text-center sm:flex-row sm:px-8 sm:text-right lg:px-10"><a href="{{ url('/') }}" class="flex items-center gap-2 text-xl font-black text-[#173c32]"><span class="text-[#c16f37]">♥</span> إحسان</a><p class="text-sm font-medium text-[#71847d]">© {{ date('Y') }} منصة إحسان. صُممت لتقريب القلوب.</p><div class="flex gap-5 text-sm font-bold text-[#587067]"><a href="#about" class="hover:text-[#c16f37]">عن المنصة</a><a href="{{ route('login') }}" class="hover:text-[#c16f37]">تسجيل الدخول</a></div></div></footer>
</body>
</html>
