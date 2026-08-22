<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="إحسان منصة تربط كبار السن بمتطوعين موثوقين لتقديم المساندة والرعاية اليومية.">
    <meta name="theme-color" content="#24472f">
    <title>إحسان | العطاء أقرب</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [data-reveal].reveal-ready {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .75s ease, transform .75s cubic-bezier(.2, .7, .2, 1);
        }

        [data-reveal="left"].reveal-ready {
            transform: translateX(-32px);
        }

        [data-reveal].reveal-ready.is-visible {
            opacity: 1;
            transform: translate(0);
        }

        .floating-card {
            animation: gentle-float 4s ease-in-out infinite;
        }

        .hero-glow {
            animation: soft-pulse 6s ease-in-out infinite;
        }

        @keyframes gentle-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes soft-pulse {
            0%, 100% { opacity: .55; transform: scale(1); }
            50% { opacity: .85; transform: scale(1.08); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="overflow-x-hidden bg-[#fbfaf6] text-[#24352a] antialiased" style="font-family: 'Alexandria', sans-serif;">
    <header class="relative z-50 border-b border-[#24472f]/10 bg-[#fbfaf6]/95 backdrop-blur-xl">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8 lg:px-10" aria-label="التنقل الرئيسي">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="العودة إلى الرئيسية">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#24472f] text-xl font-extrabold text-white shadow-[0_8px_24px_rgba(36,71,47,0.2)]">إ</span>
                <span class="text-2xl font-extrabold tracking-tight text-[#24472f]">إحسان</span>
            </a>
            <div class="hidden items-center gap-8 text-sm font-medium text-[#506057] md:flex">
                <a href="#about" class="transition hover:text-[#24472f]">عن إحسان</a>
                <a href="#services" class="transition hover:text-[#24472f]">خدماتنا</a>
                <a href="#how" class="transition hover:text-[#24472f]">كيف نعمل؟</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#24472f] px-4 py-3 text-xs font-semibold text-white transition hover:bg-[#315f40] sm:px-6 sm:text-sm">لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-3 text-xs font-semibold text-[#24472f] transition hover:bg-[#edf1e9] sm:px-5 sm:text-sm">دخول</a>
                    <a href="{{ route('register.choose') }}" class="rounded-xl bg-[#24472f] px-4 py-3 text-xs font-semibold text-white shadow-[0_8px_24px_rgba(36,71,47,0.16)] transition hover:-translate-y-0.5 hover:bg-[#315f40] sm:px-6 sm:text-sm">انضم إلينا</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="relative overflow-hidden pb-20 pt-12 sm:pb-28 sm:pt-16 lg:pb-32 lg:pt-20">
            <div class="hero-glow pointer-events-none absolute -right-24 top-4 h-72 w-72 rounded-full bg-[#dfe9d7]/70 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-32 bottom-0 h-80 w-80 rounded-full bg-[#eadfc8]/60 blur-3xl"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 sm:px-8 lg:grid-cols-[1.02fr_0.98fr] lg:px-10">
                <div class="order-2 lg:order-1" data-reveal>
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#24472f]/10 bg-white px-4 py-2 text-xs font-semibold text-[#315f40] shadow-sm sm:text-sm">
                        <span class="h-2 w-2 rounded-full bg-[#df9b52]"></span>
                        مساحة آمنة للرعاية والعطاء
                    </div>
                    <h1 class="max-w-2xl text-4xl font-extrabold leading-[1.8] text-[#1d3826] sm:text-5xl sm:leading-[1.75] lg:text-[3.75rem] lg:leading-[1.7]">
                        <span class="block">لأنّ الرعاية تبدأ</span>
                        <span class="relative mt-1 inline-block whitespace-nowrap pb-2 text-[#b87536]">
                            من القرب
                            <svg class="absolute bottom-0 right-0 w-full" viewBox="0 0 220 12" fill="none" aria-hidden="true">
                                <path d="M3 8.5C55 2.5 155 2.5 217 7" stroke="#dfb47b" stroke-width="5" stroke-linecap="round" opacity=".65" />
                            </svg>
                        </span>
                    </h1>
                    <p class="mt-7 max-w-xl text-base font-light leading-8 text-[#647168] sm:text-lg sm:leading-9">نصل كبار السن بمتطوعين موثوقين، ونحوّل الاحتياج اليومي إلى تجربة إنسانية سهلة، آمنة، ومليئة بالاهتمام.</p>
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register.choose') }}" class="group inline-flex items-center justify-center gap-3 rounded-2xl bg-[#24472f] px-7 py-4 text-sm font-semibold text-white shadow-[0_14px_32px_rgba(36,71,47,0.2)] transition hover:-translate-y-1 hover:bg-[#315f40]">
                            ابدأ رحلتك معنا
                            <svg class="h-4 w-4 transition group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </a>
                        <a href="#how" class="inline-flex items-center justify-center rounded-2xl border border-[#24472f]/15 bg-white px-7 py-4 text-sm font-semibold text-[#24472f] transition hover:border-[#24472f]/30 hover:bg-[#f4f6f1]">اكتشف كيف نساعدك</a>
                    </div>
                    <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-3 border-t border-[#24472f]/10 pt-6 text-xs font-medium text-[#647168] sm:text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-[#4d7b58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m9 12 2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            متطوعون موثوقون
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-[#4d7b58]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 15v2m6-7V8a6 6 0 0 0-12 0v2m1 11h10a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2Z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            خصوصية وأمان
                        </span>
                    </div>
                </div>

                <div class="order-1 lg:order-2" data-reveal="left">
                    <div class="relative mx-auto max-w-[470px]">
                        <div class="absolute -inset-5 rotate-3 rounded-[2.5rem] bg-[#dfe9d7]"></div>
                        <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full border border-[#b87536]/25"></div>
                        <div class="absolute -left-10 -top-10 h-36 w-36 rounded-full border border-[#b87536]/10"></div>
                        <div class="relative overflow-hidden rounded-[2.25rem] border-[6px] border-white bg-[#eee8d9] shadow-[0_30px_70px_rgba(36,71,47,0.18)]">
                            <img src="{{ asset('assets/img/hero-image.jpeg') }}" alt="متطوع يساند أحد كبار السن بابتسامة واهتمام" class="h-[490px] w-full object-cover sm:h-[600px]" fetchpriority="high">
                            <div class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-[#173020]/75 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6 text-white">
                                <p class="text-xs font-light text-white/80">رسالتنا</p>
                                <p class="mt-1 text-lg font-semibold">كرامة، اهتمام، ورفقة تصنع فرقًا.</p>
                            </div>
                        </div>
                        <div class="floating-card absolute -bottom-5 -right-3 flex items-center gap-3 rounded-2xl border border-white/80 bg-white/95 p-3.5 pl-5 shadow-[0_14px_35px_rgba(36,71,47,0.16)] backdrop-blur sm:-right-8">
                            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#edf3e9] text-[#315f40]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </span>
                            <div><p class="text-xs text-[#7a857e]">كل طلب مساعدة</p><p class="mt-0.5 text-sm font-bold text-[#24472f]">يصنع أثرًا حقيقيًا</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="border-y border-[#24472f]/10 bg-white">
            <div class="mx-auto grid max-w-7xl divide-y divide-[#24472f]/10 px-5 py-8 sm:grid-cols-3 sm:divide-x sm:divide-x-reverse sm:divide-y-0 sm:px-8 lg:px-10" data-reveal>
                @foreach ([['01', 'تسجيل بسيط', 'خطوات واضحة وسريعة'], ['02', 'تواصل موثوق', 'ضمن بيئة آمنة'], ['03', 'أثر مستمر', 'رعاية أقرب كل يوم']] as $item)
                    <div class="flex items-center gap-4 py-5 sm:justify-center sm:py-2">
                        <span class="text-3xl font-extrabold text-[#b87536]">{{ $item[0] }}</span>
                        <div><p class="font-semibold text-[#24472f]">{{ $item[1] }}</p><p class="mt-1 text-xs text-[#7a857e]">{{ $item[2] }}</p></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="services" class="py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="mx-auto max-w-2xl text-center" data-reveal>
                    <p class="text-sm font-semibold text-[#b87536]">لماذا إحسان؟</p>
                    <h2 class="mt-3 text-3xl font-extrabold leading-[1.75] text-[#1d3826] sm:text-4xl sm:leading-[1.7]">رعاية إنسانية مصممة حول احتياجك</h2>
                    <p class="mt-5 text-sm font-light leading-7 text-[#6f7b73] sm:text-base">نجمع التقنية والبُعد الإنساني لنمنح كبار السن وعائلاتهم تجربة أكثر راحة واطمئنانًا.</p>
                </div>
                <div class="mt-14 grid gap-5 md:grid-cols-3">
                    <article class="group rounded-[1.75rem] border border-[#24472f]/10 bg-white p-7 transition duration-300 hover:-translate-y-2 hover:shadow-[0_22px_50px_rgba(36,71,47,0.1)] sm:p-8" data-reveal>
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#edf3e9] text-[#315f40] transition group-hover:bg-[#24472f] group-hover:text-white">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m14-10 2 2 4-4M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </span>
                        <h3 class="mt-6 text-xl font-bold text-[#24472f]">متطوعون موثوقون</h3>
                        <p class="mt-3 text-sm font-light leading-7 text-[#718078]">بيانات واضحة وإجراءات منظمة تساعدنا على بناء مجتمع آمن وجدير بالثقة.</p>
                    </article>
                    <article class="group rounded-[1.75rem] border border-[#24472f]/10 bg-[#24472f] p-7 text-white shadow-[0_22px_50px_rgba(36,71,47,0.15)] transition duration-300 hover:-translate-y-2 sm:p-8" data-reveal>
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-[#e8c698] transition group-hover:bg-white group-hover:text-[#24472f]">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </span>
                        <h3 class="mt-6 text-xl font-bold">رعاية باحترام</h3>
                        <p class="mt-3 text-sm font-light leading-7 text-white/70">تجربة تراعي احتياجات كبار السن، وتحفظ خصوصيتهم وكرامتهم في كل خطوة.</p>
                    </article>
                    <article class="group rounded-[1.75rem] border border-[#24472f]/10 bg-white p-7 transition duration-300 hover:-translate-y-2 hover:shadow-[0_22px_50px_rgba(36,71,47,0.1)] sm:p-8" data-reveal>
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f8eee1] text-[#b87536] transition group-hover:bg-[#b87536] group-hover:text-white">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke-linecap="round" stroke-linejoin="round" /><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </span>
                        <h3 class="mt-6 text-xl font-bold text-[#24472f]">خصوصية وأمان</h3>
                        <p class="mt-3 text-sm font-light leading-7 text-[#718078]">نحافظ على بياناتك ونوفر لك تجربة استخدام واضحة ومطمئنة من البداية.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="how" class="relative overflow-hidden bg-[#edf2e9] py-20 sm:py-28">
            <div class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 rounded-full border-[45px] border-white/45"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 sm:px-8 lg:grid-cols-[0.8fr_1.2fr] lg:px-10">
                <div data-reveal>
                    <p class="text-sm font-semibold text-[#b87536]">ثلاث خطوات فقط</p>
                    <h2 class="mt-3 text-3xl font-extrabold leading-[1.8] text-[#1d3826] sm:text-4xl sm:leading-[1.75]">طريقك إلى المساعدة أبسط مما تتخيّل</h2>
                    <p class="mt-5 text-sm font-light leading-8 text-[#6f7b73] sm:text-base">أنشئ حسابك، أخبرنا بما تحتاج، ودع إحسان يقرّب لك الشخص المناسب.</p>
                    <a href="{{ route('register.choose') }}" class="mt-7 inline-flex items-center gap-2 text-sm font-bold text-[#24472f] transition hover:text-[#b87536]">إنشاء حساب الآن <span aria-hidden="true">←</span></a>
                </div>
                <ol class="grid gap-4" data-reveal="left">
                    @foreach ([['01', 'اختر نوع حسابك', 'سجّل بصفتك كبير سن يحتاج إلى المساندة، أو متطوعًا يرغب في تقديمها.', '#24472f'], ['02', 'أكمل بياناتك بسهولة', 'نموذج واضح ومقسّم إلى مراحل قصيرة يساعدك على إتمام التسجيل براحة.', '#b87536'], ['03', 'ابدأ تجربتك مع إحسان', 'تابع ملفك وطلباتك وخدماتك من مكان واحد وبواجهة سهلة الاستخدام.', '#789066']] as $step)
                        <li class="flex gap-5 rounded-3xl border border-[#24472f]/10 bg-white p-5 shadow-sm sm:p-6">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-sm font-bold text-white" style="background-color: {{ $step[3] }}">{{ $step[0] }}</span>
                            <div><h3 class="font-bold text-[#24472f]">{{ $step[1] }}</h3><p class="mt-2 text-sm font-light leading-6 text-[#718078]">{{ $step[2] }}</p></div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        <section class="py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="relative overflow-hidden rounded-[2rem] bg-[#24472f] px-6 py-14 text-center text-white shadow-[0_25px_60px_rgba(36,71,47,0.18)] sm:px-12 sm:py-16" data-reveal>
                    <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full border-[44px] border-white/5"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-12 h-64 w-64 rounded-full bg-[#b87536]/20 blur-2xl"></div>
                    <div class="relative mx-auto max-w-2xl">
                        <p class="text-sm font-medium text-[#e8c698]">ابدأ اليوم</p>
                        <h2 class="mt-3 text-3xl font-extrabold leading-[1.8] sm:text-4xl sm:leading-[1.75]">خطوة صغيرة منك، أثر كبير في حياة إنسان</h2>
                        <p class="mx-auto mt-5 max-w-xl text-sm font-light leading-7 text-white/70 sm:text-base">انضم إلى مجتمع إحسان، وكن جزءًا من تجربة رعاية أكثر قربًا وإنسانية.</p>
                        <a href="{{ route('register.choose') }}" class="mt-8 inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-sm font-bold text-[#24472f] transition hover:-translate-y-1 hover:bg-[#f4eadb]">إنشاء حساب جديد</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-[#24472f]/10 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-5 py-8 text-center sm:flex-row sm:px-8 sm:text-right lg:px-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#24472f] font-extrabold text-white">إ</span><span class="text-xl font-extrabold text-[#24472f]">إحسان</span></a>
            <p class="text-xs font-light text-[#7a857e]">© {{ date('Y') }} منصة إحسان. جميع الحقوق محفوظة.</p>
            <div class="flex items-center gap-5 text-xs font-medium text-[#647168]"><a href="#about" class="transition hover:text-[#24472f]">عن المنصة</a><a href="#services" class="transition hover:text-[#24472f]">الخدمات</a></div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('[data-reveal]');

            if (!('IntersectionObserver' in window)) {
                return;
            }

            items.forEach((item, index) => {
                item.classList.add('reveal-ready');
                item.style.transitionDelay = `${Math.min(index % 3, 2) * 90}ms`;
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.14 });

            items.forEach((item) => observer.observe(item));
        });
    </script>
</body>
</html>
