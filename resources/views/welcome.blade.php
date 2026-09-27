<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="أنيس — خدمات يومية موثوقة لمبتوري الأطراف وذوي الإعاقة وكبار السن.">
    <meta name="theme-color" content="#153f36">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/anees-logo.png') }}">
    <title>أنيس | المساعدة أقرب مما تتخيّل</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --ink:#153f36; --ink-2:#24584c; --mint:#dff2e8; --mint-soft:#f2f8f4; --coral:#e9785d; --coral-soft:#fff0ea; --sand:#f7f1e8; --paper:#fffdf9; --muted:#64736e; --line:#dfe9e4; --shadow:0 18px 55px rgba(21,63,54,.10); }
        body { background:var(--paper); color:var(--ink); }
        .dot-grid { background-image:radial-gradient(rgba(21,63,54,.12) 1px,transparent 1px); background-size:22px 22px; }
        .service-card { transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
        .service-card:hover { transform:translateY(-6px); box-shadow:var(--shadow); border-color:rgba(233,120,93,.35); }
        [data-reveal].reveal-ready { opacity:0; transform:translateY(22px); transition:opacity .6s ease,transform .6s ease; }
        [data-reveal].reveal-ready.is-visible { opacity:1; transform:none; }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; animation:none!important; transition:none!important; } }
    </style>
</head>
<body class="overflow-x-hidden antialiased selection:bg-[#dff2e8] selection:text-[#153f36]">
    <a href="#main-content" class="fixed right-4 top-3 z-[60] -translate-y-20 rounded-xl bg-[var(--ink)] px-4 py-3 text-sm font-bold text-white focus:translate-y-0">انتقل إلى المحتوى</a>

    <header class="sticky top-0 z-50 border-b border-[var(--line)] bg-[var(--paper)]/95 backdrop-blur-xl">
        <nav aria-label="التنقل الرئيسي" class="mx-auto flex h-[76px] max-w-7xl items-center justify-between px-5 sm:px-8 lg:px-10">
            <a href="{{ url('/') }}" class="flex items-center" aria-label="أنيس — الصفحة الرئيسية">
                <img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس" class="h-[68px] w-[92px] object-contain" width="92" height="68">
            </a>
            <div class="hidden items-center gap-8 text-sm font-bold text-[var(--muted)] md:flex">
                <a href="#services" class="transition hover:text-[var(--coral)]">خدماتنا</a>
                <a href="#assistant" class="transition hover:text-[var(--coral)]">المساعد الذكي</a>
                <a href="#how" class="transition hover:text-[var(--coral)]">كيف نساعدك؟</a>
                <a href="#trust" class="transition hover:text-[var(--coral)]">لماذا أنيس؟</a>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ Auth::user()->isProvider() ? route('provider.dashboard') : route('dashboard') }}" class="rounded-xl bg-[var(--ink)] px-4 py-3 text-xs font-extrabold text-white transition hover:bg-[var(--ink-2)] sm:px-5">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-xl px-4 py-3 text-xs font-extrabold text-[var(--ink)] transition hover:bg-[var(--mint-soft)] sm:inline-flex">تسجيل الدخول</a>
                    <a href="{{ route('register.choose') }}" class="rounded-xl bg-[var(--ink)] px-4 py-3 text-xs font-extrabold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[var(--ink-2)] sm:px-5">إنشاء حساب</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main-content">
        <section class="relative isolate overflow-hidden pb-16 pt-12 sm:pb-24 sm:pt-20 lg:pt-24">
            <div class="dot-grid pointer-events-none absolute inset-y-0 left-0 -z-10 w-2/5 opacity-50 [mask-image:linear-gradient(to_right,black,transparent)]"></div>
            <div class="pointer-events-none absolute -right-40 -top-52 -z-10 h-[560px] w-[560px] rounded-full bg-[var(--mint)] blur-3xl"></div>
            <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 sm:px-8 lg:grid-cols-[1.02fr_.98fr] lg:gap-20 lg:px-10">
                <div data-reveal>
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[var(--line)] bg-white px-4 py-2 text-[11px] font-extrabold text-[var(--ink-2)] shadow-sm sm:text-xs"><span class="h-2 w-2 rounded-full bg-[var(--coral)]"></span>لمبتوري الأطراف · ذوي الإعاقة · كبار السن</div>
                    <h1 class="max-w-2xl text-[2.6rem] font-black leading-[1.35] tracking-[-.035em] sm:text-6xl sm:leading-[1.3]">كل مساعدة تحتاجها، <span class="relative whitespace-nowrap text-[var(--coral)]">تصل إليك.</span></h1>
                    <p class="mt-6 max-w-xl text-base font-medium leading-8 text-[var(--muted)] sm:text-lg sm:leading-9">أنيس يربطك بأشخاص موثوقين يساعدونك في احتياجاتك اليومية؛ من شراء الدواء والأغراض إلى المرافقة والمساعدة في المنزل.</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register.choose') }}" class="inline-flex min-h-14 items-center justify-center gap-3 rounded-2xl bg-[var(--coral)] px-7 text-sm font-black text-white shadow-[0_12px_30px_rgba(233,120,93,.28)] transition hover:-translate-y-1 hover:bg-[#dc6a51]">اطلب مساعدة الآن <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                        <a href="#services" class="inline-flex min-h-14 items-center justify-center rounded-2xl border-2 border-[var(--line)] bg-white px-7 text-sm font-black text-[var(--ink)] transition hover:border-[var(--ink)]">استكشف الخدمات</a>
                    </div>
                    <div class="mt-9 flex flex-wrap gap-x-6 gap-y-3 text-xs font-bold text-[var(--muted)]">
                        <span class="flex items-center gap-2"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-[var(--mint)] text-[var(--ink)]">✓</span> مقدمو خدمة موثوقون</span>
                        <span class="flex items-center gap-2"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-[var(--mint)] text-[var(--ink)]">✓</span> طلب بسيط وواضح</span>
                        <span class="flex items-center gap-2"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-[var(--mint)] text-[var(--ink)]">✓</span> متابعة حتى الإنجاز</span>
                    </div>
                </div>
                <div class="relative" data-reveal>
                    <div class="absolute -inset-5 -z-10 rotate-3 rounded-[2.5rem] bg-[var(--mint)]"></div>
                    <div class="overflow-hidden rounded-[2rem] border-[7px] border-white bg-white shadow-[var(--shadow)]"><img src="{{ asset('assets/img/hero-community-illustration.png') }}" alt="مجموعة من كبار السن وذوي الإعاقة ومبتوري الأطراف برفقة مقدمة خدمة من أنيس" class="aspect-[4/3] w-full object-cover" fetchpriority="high"></div>
                    <div class="absolute -bottom-7 right-3 flex max-w-[250px] items-center gap-3 rounded-2xl border border-[var(--line)] bg-white p-4 shadow-xl sm:right-7">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--coral-soft)] text-[var(--coral)]"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg></span>
                        <div><p class="text-sm font-black">خدمة باهتمام</p><p class="mt-1 text-[11px] font-medium text-[var(--muted)]">لأن راحتك وكرامتك أولويتنا</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="bg-[var(--mint-soft)] py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end" data-reveal>
                    <div><p class="text-xs font-black tracking-[.18em] text-[var(--coral)]">خدمات أنيس</p><h2 class="mt-3 text-3xl font-black tracking-tight sm:text-5xl">ماذا تحتاج اليوم؟</h2></div>
                    <p class="max-w-md text-sm font-medium leading-7 text-[var(--muted)] sm:text-base">اختر الخدمة المناسبة، حدّد الوقت والمكان، واترك الباقي علينا.</p>
                </div>
                @php
                    $services = [
                        ['bag', 'شراء أغراض', 'شراء وتوصيل الاحتياجات اليومية من السوق حتى باب البيت.', '01'],
                        ['medicine', 'شراء دواء', 'إحضار الأدوية والمستلزمات التي تحتاجها من الصيدلية.', '02'],
                        ['escort', 'المرافقة', 'مرافقتك للطبيب أو المستشفى أو في مشاويرك المختلفة.', '03'],
                        ['home', 'المساعدة المنزلية', 'مساعدتك في المهام اليومية البسيطة داخل المنزل.', '04'],
                        ['visit', 'الزيارة الاجتماعية', 'زيارة ودّية للمؤانسة والحديث والاطمئنان عليك.', '05'],
                        ['support', 'طلب الدعم', 'ربطك بمقدم خدمة متخصص؛ فني أو كهربائي أو سباك.', '06'],
                    ];
                @endphp
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($services as [$icon, $title, $description, $number])
                        <article class="service-card group relative overflow-hidden rounded-[1.6rem] border border-[var(--line)] bg-white p-6 sm:p-7" data-reveal>
                            <span class="absolute left-6 top-6 text-xs font-black tracking-widest text-[var(--line)]">{{ $number }}</span>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--coral-soft)] text-[var(--coral)] transition group-hover:bg-[var(--coral)] group-hover:text-white">
                                @if($icon === 'bag')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M6 8h12l1 13H5L6 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                                @elseif($icon === 'medicine')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="m10.5 20.5 10-10a4.24 4.24 0 0 0-6-6l-10 10a4.24 4.24 0 0 0 6 6Z"/><path d="m8.5 10.5 5 5"/></svg>
                                @elseif($icon === 'escort')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="9" cy="5" r="2.5"/><path d="M5 21v-5.5a4 4 0 0 1 8 0V21M16 8l3 3-3 3M13 11h6"/></svg>
                                @elseif($icon === 'home')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="m3 11 9-8 9 8v10h-6v-6H9v6H3V11Z"/></svg>
                                @elseif($icon === 'visit')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3 1.7-5.1A7 7 0 0 1 3 12c0-4.4 4-8 9-8s9 3.6 9 8v3Z"/><path d="M8 12h.01M12 12h.01M16 12h.01"/></svg>
                                @else
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M14.7 6.3a4 4 0 0 0-5-5L12 3.6 3.6 12 1 19l7-2.6L16.4 8l2.3 2.3a4 4 0 0 0-4-4Z"/><path d="m12 3.6 4.4 4.4"/></svg>
                                @endif
                            </div>
                            <h3 class="mt-6 text-xl font-black">{{ $title }}</h3>
                            <p class="mt-3 min-h-14 text-sm font-medium leading-7 text-[var(--muted)]">{{ $description }}</p>
                            <a href="{{ route('register.choose') }}" class="mt-5 inline-flex items-center gap-2 text-xs font-black text-[var(--coral)] after:content-['←']">اطلب الخدمة</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="assistant" class="relative isolate overflow-hidden bg-[var(--ink)] py-20 text-white sm:py-28">
            <div class="dot-grid pointer-events-none absolute inset-0 -z-10 opacity-[.07]"></div>
            <div class="pointer-events-none absolute -left-32 top-12 -z-10 h-80 w-80 rounded-full bg-[var(--coral)]/20 blur-3xl"></div>
            <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 sm:px-8 lg:grid-cols-2 lg:gap-20 lg:px-10">
                <div data-reveal>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black text-[var(--mint)]">
                        <span class="relative flex h-2.5 w-2.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[var(--coral)] opacity-75"></span><span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[var(--coral)]"></span></span>
                        مساعد أنيس الذكي
                    </div>
                    <h2 class="mt-5 max-w-xl text-3xl font-black tracking-tight sm:text-5xl sm:leading-[1.45]">احكِ ما تحتاجه،<br><span class="text-[var(--coral)]">وأنيس يرتّب طلبك.</span></h2>
                    <p class="mt-6 max-w-xl text-sm font-medium leading-8 text-white/70 sm:text-base">إذا كانت الكتابة أو التنقل بين الشاشات متعبًا، يساعدك أنيس بالصوت خطوة بخطوة لاختيار الخدمة وتحديد المكان والموعد ومراجعة الطلب قبل إرساله.</p>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/[.06] p-4"><svg class="h-6 w-6 text-[var(--coral)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/></svg><h3 class="mt-3 text-sm font-black">تحدّث بطريقتك</h3><p class="mt-1 text-[11px] leading-5 text-white/55">استخدم صوتك أو اكتب طلبك.</p></div>
                        <div class="rounded-2xl border border-white/10 bg-white/[.06] p-4"><svg class="h-6 w-6 text-[var(--coral)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/><circle cx="12" cy="12" r="4"/></svg><h3 class="mt-3 text-sm font-black">إرشاد خطوة بخطوة</h3><p class="mt-1 text-[11px] leading-5 text-white/55">أسئلة بسيطة وواضحة لإكمال الطلب.</p></div>
                        <div class="rounded-2xl border border-white/10 bg-white/[.06] p-4"><svg class="h-6 w-6 text-[var(--coral)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12.5 9 17l11-11"/></svg><h3 class="mt-3 text-sm font-black">تأكيد قبل الإرسال</h3><p class="mt-1 text-[11px] leading-5 text-white/55">راجع التفاصيل وعدّلها براحتك.</p></div>
                    </div>

                    <a href="{{ route('register.choose') }}" class="mt-8 inline-flex min-h-14 items-center justify-center gap-3 rounded-2xl bg-[var(--coral)] px-7 text-sm font-black text-white shadow-[0_12px_30px_rgba(0,0,0,.2)] transition hover:-translate-y-1 hover:bg-[#dc6a51]">
                        جرّب مساعد أنيس
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M19 12H5m6 6-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>

                <div class="relative mx-auto w-full max-w-[500px]" data-reveal>
                    <div class="absolute -inset-4 rotate-2 rounded-[2.5rem] border border-white/10 bg-white/[.04]"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/15 bg-[#f7faf8] text-[var(--ink)] shadow-2xl">
                        <div class="flex items-center justify-between bg-[var(--ink-2)] px-5 py-4 text-white">
                            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="7" width="16" height="12" rx="4"/><path d="M9 12h.01M15 12h.01M9 16h6M12 3v4"/></svg></span><div><p class="text-sm font-black">مساعد أنيس</p><p class="text-[10px] font-bold text-white/60">جاهز لمساعدتك</p></div></div>
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-[var(--mint)]"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> متصل الآن</span>
                        </div>
                        <div class="space-y-4 p-5 sm:p-7">
                            <div class="flex justify-start"><div class="max-w-[85%] rounded-2xl rounded-tr-sm bg-white px-4 py-3 text-xs font-bold leading-6 shadow-sm">أهلًا فيك! شو بتحب أساعدك اليوم؟</div></div>
                            <div class="flex justify-end"><div class="max-w-[85%] rounded-2xl rounded-tl-sm bg-[var(--mint)] px-4 py-3 text-xs font-bold leading-6">بدي حدا يجيبلي الدواء بكرا الصبح</div></div>
                            <div class="flex justify-start"><div class="max-w-[85%] rounded-2xl rounded-tr-sm bg-white px-4 py-3 text-xs font-bold leading-6 shadow-sm">أكيد. سأساعدك نحدد الصيدلية والمكان والموعد.</div></div>
                            <div class="grid grid-cols-2 gap-2 pt-1"><span class="rounded-xl border border-[var(--line)] bg-white px-3 py-3 text-center text-[11px] font-black">📍 تحديد المكان</span><span class="rounded-xl border border-[var(--line)] bg-white px-3 py-3 text-center text-[11px] font-black">🕘 تحديد الموعد</span></div>
                        </div>
                        <div class="flex items-center gap-2 border-t border-[var(--line)] bg-white p-4"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--coral)] text-white"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/></svg></span><div class="flex h-11 flex-1 items-center rounded-xl bg-[var(--mint-soft)] px-4 text-[11px] font-bold text-[var(--muted)]">احكِ أو اكتب ما تحتاجه...</div><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[var(--ink)] text-white">←</span></div>
                    </div>
                    <div class="absolute -bottom-5 -right-3 flex items-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-black text-[var(--ink)] shadow-xl sm:right-6"><span class="text-base">🔊</span> أزرار ناطقة وواضحة</div>
                </div>
            </div>
        </section>

        <section id="how" class="py-20 sm:py-28">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[.85fr_1.15fr] lg:items-center lg:gap-20 lg:px-10">
                <div class="relative" data-reveal>
                    <img src="{{ asset('assets/img/hero_illustration.jpg') }}" alt="أشخاص يقدمون المساندة لذوي الإعاقة وكبار السن" loading="lazy" class="w-full rounded-[2rem] border border-[var(--line)] shadow-[var(--shadow)]">
                    <div class="absolute -bottom-5 -left-3 rounded-2xl bg-[var(--ink)] px-5 py-4 text-white shadow-lg sm:left-6"><p class="text-2xl font-black">3 خطوات</p><p class="mt-1 text-[11px] font-bold text-white/65">وتصلك المساعدة</p></div>
                </div>
                <div data-reveal>
                    <p class="text-xs font-black tracking-[.18em] text-[var(--coral)]">ببساطة ووضوح</p><h2 class="mt-3 text-3xl font-black tracking-tight sm:text-5xl">من طلبك إلى بابك</h2>
                    <div class="mt-9 space-y-4">
                        @foreach([['1','اختر ما تحتاجه','حدّد الخدمة المناسبة وأخبرنا بالوقت والمكان.'],['2','نرتّب لك المساعدة','نربط طلبك بمقدم خدمة مناسب وموثوق.'],['3','تابع طلبك بسهولة','ابقَ مطمئناً من قبول الطلب وحتى اكتمال الخدمة.']] as $step)
                            <div class="flex gap-4 rounded-2xl border border-transparent p-4 transition hover:border-[var(--line)] hover:bg-[var(--mint-soft)]"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--mint)] text-sm font-black">{{ $step[0] }}</span><div><h3 class="text-base font-black">{{ $step[1] }}</h3><p class="mt-1 text-sm font-medium leading-7 text-[var(--muted)]">{{ $step[2] }}</p></div></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="trust" class="bg-[var(--sand)] py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="mx-auto max-w-2xl text-center" data-reveal><p class="text-xs font-black tracking-[.18em] text-[var(--coral)]">راحة، ثقة، وكرامة</p><h2 class="mt-3 text-3xl font-black tracking-tight sm:text-5xl">أنيس موجود ليخفّف عنك</h2><p class="mt-5 text-sm font-medium leading-7 text-[var(--muted)] sm:text-base">صممنا التجربة لتكون سهلة وواضحة، ولتبقى أنت وعائلتك على اطلاع في كل خطوة.</p></div>
                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    @foreach([['أشخاص موثوقون','نتحقق من مقدمي الخدمة قبل استقبالهم للطلبات.','shield'],['احترام وخصوصية','نتعامل مع احتياجك بمسؤولية ونحفظ خصوصيتك وكرامتك.','heart'],['متابعة مستمرة','تعرف حالة طلبك من لحظة إرساله وحتى إنجازه.','check']] as [$title,$text,$type])
                        <div class="rounded-[1.5rem] bg-white p-7 shadow-sm" data-reveal>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--mint)]">
                                @if($type === 'shield') <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                                @elseif($type === 'heart') <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                                @else <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.7 2.7L16.5 9"/></svg> @endif
                            </div>
                            <h3 class="mt-5 text-lg font-black">{{ $title }}</h3><p class="mt-2 text-sm font-medium leading-7 text-[var(--muted)]">{{ $text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="px-5 py-20 sm:px-8 sm:py-28">
            <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-[var(--ink)] px-6 py-14 text-center text-white shadow-[var(--shadow)] sm:px-12 sm:py-20" data-reveal>
                <div class="dot-grid pointer-events-none absolute inset-0 opacity-[.08]"></div>
                <div class="relative mx-auto max-w-2xl"><span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-xl">♥</span><h2 class="mt-5 text-3xl font-black tracking-tight sm:text-5xl">جاهز نكون عوناً لك؟</h2><p class="mx-auto mt-5 max-w-xl text-sm font-medium leading-7 text-white/70 sm:text-base">اطلب الخدمة التي تحتاجها، أو انضم إلى مقدمي الخدمة وكن سبباً في جعل يوم شخص آخر أسهل.</p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row"><a href="{{ route('register.choose') }}" class="rounded-2xl bg-[var(--coral)] px-8 py-4 text-sm font-black text-white transition hover:-translate-y-1 hover:bg-[#dc6a51]">أحتاج مساعدة</a><a href="{{ route('frontend.volunteer.register') }}" class="rounded-2xl border border-white/25 bg-white/5 px-8 py-4 text-sm font-black text-white transition hover:bg-white/10">أريد تقديم المساعدة</a></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-[var(--line)] bg-white"><div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-5 py-8 text-center sm:flex-row sm:px-8 sm:text-right lg:px-10"><a href="{{ url('/') }}" aria-label="أنيس — الصفحة الرئيسية"><img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس" class="h-20 w-24 object-contain" width="96" height="80" loading="lazy"></a><p class="text-xs font-medium text-[var(--muted)]">© {{ date('Y') }} أنيس. خدمات أقرب، وحياة أسهل.</p><div class="flex gap-5 text-xs font-bold text-[var(--muted)]"><a href="#services" class="hover:text-[var(--coral)]">الخدمات</a><a href="#how" class="hover:text-[var(--coral)]">كيف نساعدك؟</a><a href="{{ route('login') }}" class="hover:text-[var(--coral)]">دخول</a></div></div></footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('[data-reveal]');
            if (!('IntersectionObserver' in window)) return;
            elements.forEach(element => element.classList.add('reveal-ready'));
            const observer = new IntersectionObserver(entries => entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } }), { threshold:.1 });
            elements.forEach(element => observer.observe(element));
        });
    </script>
</body>
</html>
