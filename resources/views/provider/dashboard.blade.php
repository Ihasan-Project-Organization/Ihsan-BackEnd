<x-provider-layout>
    <style>
        .provider-dashboard-hero{position:relative;overflow:hidden;border:1px solid #dce5d3;border-radius:1.75rem;background:linear-gradient(135deg,#fff 0%,#f7faf4 68%,#eef4e8 100%);padding:1.75rem;box-shadow:0 14px 36px rgba(42,63,26,.09);color:#25351a}
        .provider-dashboard-hero:before{content:'';position:absolute;inset-block:0;right:0;width:.45rem;background:linear-gradient(180deg,#31421e,#7c995f)}
        .provider-dashboard-hero .provider-hero-copy{max-width:42rem;padding-inline-start:.5rem}
        .provider-dashboard-hero .provider-hero-copy>div:first-child>span{border-color:#d7e1cd!important;background:#f1f5ed!important;color:#42552f!important;box-shadow:none!important}
        .provider-dashboard-hero .provider-hero-copy>div:first-child form button{border-color:#cbd8bf!important;background:#fff!important;color:#365321!important}
        .provider-dashboard-hero .provider-hero-title{max-width:38rem;color:#203014;font-size:clamp(1.7rem,3vw,2.5rem);line-height:1.3;letter-spacing:-.035em}
        .provider-dashboard-hero .provider-hero-description{max-width:41rem;color:#66735c!important;font-size:.82rem;line-height:1.9}
        .provider-dashboard-hero .provider-hero-copy .mt-6 a:first-child{background:#31421e!important;color:#fff!important;box-shadow:0 8px 20px rgba(49,66,30,.2)}
        .provider-dashboard-hero .provider-hero-copy .mt-6 a:last-child{border-color:#ccd8c1!important;background:#fff!important;color:#31421e!important}
        .provider-dashboard-hero .provider-hero-side{border:1px solid rgba(255,255,255,.12)!important;background:linear-gradient(145deg,#2b411b,#3f5c28)!important;box-shadow:0 12px 28px rgba(42,63,26,.18)!important;color:#fff}
        .provider-dashboard-hero .provider-hero-side .text-white{color:#fff!important}
        .provider-dashboard-hero .provider-hero-side .text-slate-200{color:#e5eadf!important}
        .provider-dashboard-hero .provider-hero-side .text-\[\#dfe6d5\]{color:#dfe6d5!important}
        .provider-dashboard-hero>div.absolute{display:none!important}
        .provider-dashboard .provider-dashboard-hero{padding:1.15rem 1.25rem;border-radius:1.1rem;box-shadow:0 8px 22px rgba(42,63,26,.07)}
        .provider-dashboard-hero .provider-hero-copy>div:first-child{display:none}
        .provider-dashboard-hero .provider-hero-copy{width:100%;max-width:none}
        .provider-dashboard-hero .provider-hero-side{display:none}
        .provider-dashboard-hero>div.relative{gap:1rem}
        .provider-dashboard-hero .provider-hero-copy{max-width:46rem;padding-inline-start:.25rem}
        .provider-dashboard-hero .provider-hero-title{margin-top:.7rem;font-size:clamp(1.55rem,2.2vw,2rem);line-height:1.2}
        .provider-dashboard-hero .provider-hero-description{margin-top:.35rem;max-width:44rem;font-size:.72rem;line-height:1.7}
        .provider-dashboard-hero .provider-hero-copy>div:first-child>span,
        .provider-dashboard-hero .provider-hero-copy>div:first-child form button{padding:.3rem .65rem;font-size:.62rem}
        .provider-dashboard-hero .provider-hero-copy .mt-6{display:none}
        .provider-dashboard-hero .provider-hero-copy .mt-6 a{padding:.55rem .85rem;border-radius:.7rem;font-size:.68rem;box-shadow:none!important}
        .provider-dashboard-hero .provider-hero-side{max-width:22rem;padding:.85rem!important;border-radius:.85rem!important;box-shadow:0 7px 18px rgba(42,63,26,.14)!important}
        .provider-dashboard-hero .provider-hero-side h2{margin-top:.55rem;font-size:.82rem}
        .provider-dashboard-hero .provider-hero-side p{font-size:.66rem;line-height:1.6}
        .provider-dashboard-hero .provider-hero-side .mt-4{margin-top:.65rem}
        .provider-dashboard .dashboard-stats{gap:.65rem}
        .provider-dashboard .dashboard-stat-card{min-height:106px;padding:.8rem;border-radius:1rem}
        .provider-dashboard .dashboard-stat-card .h-10{width:2.1rem;height:2.1rem;border-radius:.7rem;font-size:.9rem}
        .provider-dashboard .dashboard-stat-card p.text-3xl{margin-top:.45rem;font-size:1.55rem;line-height:1}
        .provider-dashboard .dashboard-main-grid{gap:1rem}
        .provider-dashboard .dashboard-section-title{font-size:1rem;line-height:1.4}
        @media(min-width:1025px){.provider-dashboard .dashboard-main-grid{grid-template-columns:minmax(0,1.55fr) minmax(260px,.45fr)}}
        @media(max-width:640px){.provider-dashboard-hero{padding:1.25rem;border-radius:1.35rem}.provider-dashboard-hero .provider-hero-title{font-size:1.65rem}.provider-dashboard-hero .provider-hero-side{border-radius:1.15rem!important}}
    </style>
    <div class="provider-dashboard space-y-8">

        {{-- تنبيهات النظام --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm flex items-center justify-between animate-fadeIn">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800 text-lg"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                    <p class="text-xs sm:text-sm font-bold">
                        {{ match(session('status')) {
                            'task-accepted' => 'تم قبول الطلب بنجاح ونقله إلى قائمة طلباتك.',
                            'task-dismissed' => 'تم تجاوز الطلب وإخفاؤه من القائمة دون التأثير على تقييمك.',
                            'service-started' => 'تم تسجيل بدء تقديم الخدمة بنجاح.',
                            'service-finished' => 'تم إرسال ملخص التنفيذ إلى طالب المساعدة وبانتظار تأكيده.',
                            'delay-reported' => 'تم إرسال إشعار التأخير إلى طالب المساعدة وتحديث الموعد المتوقع.',
                            'apology-completed' => 'تم تسجيل اعتذارك وفصل الإسناد وإعادة نشر الطلب بنجاح.',
                            'settings-updated' => 'تم حفظ حالة التوفر وإعدادات الخدمة بنجاح.',
                            'certificate-issued' => 'تهانينا! تم إصدار شهادة التطوع الرقمية بنجاح.',
                            default => 'تم تنفيذ العملية بنجاح.'
                        } }}
                    </p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-900 cursor-pointer"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm flex items-center justify-between animate-fadeIn">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100 text-rose-800 text-lg"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i></span>
                    <p class="text-xs sm:text-sm font-bold">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-rose-600 hover:text-rose-900 cursor-pointer"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            </div>
        @endif

        {{-- 1. بطاقة الترحيب والداشبورد الرئيسية الفاخرة --}}
        @php($displayProviderName = trim(preg_replace('/\s*\((?:مقدم خدمة|متطوع)\)\s*/u', '', $provider->name)))
        <section class="provider-dashboard-hero">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="provider-hero-copy">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-[#dfe6d5] backdrop-blur-sm border border-white/10">
                            <span><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i></span>
                            <span>مقدم خدمة معتمد</span>
                        </span>

                        {{-- شارة المستوى Tier --}}
                        <span class="inline-flex items-center gap-1.5 rounded-full {{ $tier === 3 ? 'bg-amber-400/20 text-amber-300 border-amber-300/30' : ($tier === 2 ? 'bg-emerald-400/20 text-emerald-300 border-emerald-300/30' : 'bg-white/10 text-white border-white/20') }} px-3 py-1 text-xs font-black border backdrop-blur-sm">
                            <span><i class="fa-solid fa-trophy" aria-hidden="true"></i></span>
                            <span>المستوى {{ $tier }} (Tier {{ $tier }})</span>
                        </span>

                        {{-- شارة التوفر الحالية --}}
                        <form method="POST" action="{{ route('provider.availability.update') }}" class="inline">
                            @csrf
                            <input type="hidden" name="is_available" value="{{ $setting?->is_available ? '0' : '1' }}">
                            <button type="submit" title="اضغط لتغيير حالة التوفر سريعاً"
                                class="inline-flex items-center gap-1.5 rounded-full {{ $setting?->is_available ? 'bg-emerald-500/25 text-emerald-200 border-emerald-400/30' : 'bg-rose-500/25 text-rose-200 border-rose-400/30' }} px-3 py-1 text-xs font-bold border backdrop-blur-sm transition hover:scale-105 cursor-pointer">
                                <span class="h-2 w-2 rounded-full {{ $setting?->is_available ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                                <span>{{ $setting?->is_available ? 'متاح لاستقبال المهام' : 'غير متاح مؤقتاً' }}</span>
                                <span class="text-[10px] underline opacity-80">(تغيير)</span>
                            </button>
                        </form>
                    </div>

                    <h1 class="provider-hero-title mt-4 font-black">مرحبًا، {{ $displayProviderName }}</h1>
                    <p class="provider-hero-description mt-2">
                        من هنا تتابع مهامك وتصل إلى طلبات المساعدة المناسبة بسرعة ووضوح.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('provider.available') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-xs sm:text-sm font-bold text-[#31421e] shadow-lg hover:bg-[#eef2e8] transition hover:-translate-y-0.5">
                            <span><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> تصفح الطلبات المتاحة ({{ $availableCount }})</span>
                        </a>
                        <a href="{{ route('provider.tasks') }}"
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/25 bg-white/10 px-4 py-3 text-xs sm:text-sm font-bold text-white hover:bg-white/20 transition">
                            <span><i class="fa-solid fa-clipboard-list" aria-hidden="true"></i> متابعة طلباتي</span>
                        </a>
                    </div>
                </div>

                {{-- بطاقة المهمة القادمة أو التقدم في المستوى --}}
                @if ($nextTask)
                    <div class="provider-hero-side rounded-3xl p-5 sm:p-6 max-w-md w-full">
                        <div class="flex items-center justify-between gap-4">
                            <span class="rounded-xl bg-amber-400/20 px-3 py-1 text-xs font-black text-amber-300 border border-amber-300/20">
                                <i class="fa-regular fa-clock" aria-hidden="true"></i> الموعد {{ $nextTask->scheduled_at->diffForHumans() }}
                            </span>
                            <span class="text-xs font-bold text-slate-200 dir-ltr font-mono">{{ $nextTask->scheduled_at->translatedFormat('h:i A') }}</span>
                        </div>
                        <h2 class="mt-3 text-base font-black text-white">مهمتك القادمة</h2>
                        <p class="mt-1 text-xs leading-5 text-[#dfe6d5]">
                            طلب <span class="font-bold text-white">"{{ $nextTask->service_type_label }}"</span> في <span class="font-bold text-white">{{ $nextTask->location }}</span>.
                        </p>
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('provider.tasks', ['tab' => 'upcoming']) }}"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-white px-4 py-2 text-xs font-bold text-[#31421e] hover:bg-[#eef2e8] transition shadow">
                                <span>عرض وإدارة المهمة</span>
                                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="provider-hero-side rounded-3xl p-5 sm:p-6 max-w-md w-full">
                        <div class="flex items-center justify-between">
                            <span class="rounded-xl bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-200 border border-emerald-400/20">
                                مستوى التميز {{ $tier }}
                            </span>
                            <span class="text-xs text-white/80 font-bold">{{ $tierProgress }}%</span>
                        </div>
                        <h2 class="mt-3 text-base font-black text-white">
                            {{ $tier === 3 ? 'أعلى مستوى ثقة (Tier 3)' : 'خطوتك نحو المستوى التالي' }}
                        </h2>
                        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full bg-amber-400 transition-all duration-500 rounded-full" style="width: {{ $tierProgress }}%"></div>
                        </div>
                        <p class="mt-2 text-xs leading-5 text-[#dfe6d5]">
                            @if ($tier < 3)
                                أنجزت <strong class="text-white">{{ $completedCount }}</strong> مهمة، يتبقى <strong class="text-amber-300">{{ $tasksToNextTier }}</strong> مهمة للترقية لـ Tier {{ $tier + 1 }}.
                            @else
                                أحسنت! أنت في أعلى مستوى معتمد لدى منصة أنيس مع كامل الصلاحيات والأولوية.
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            {{-- دوائر زخرفية خلفية --}}
            <div class="absolute -bottom-20 -left-16 hidden h-72 w-72 rounded-full bg-[#718256]/30 blur-2xl lg:block"></div>
            <div class="absolute -top-20 -right-16 hidden h-72 w-72 rounded-full bg-[#31421e]/40 blur-2xl lg:block"></div>
        </section>

        {{-- 2. بطاقات الإحصائيات الأربعة بتصميم حديث --}}
        <div class="dashboard-stats grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- طلبات متاحة --}}
            <a href="{{ route('provider.available') }}" class="dashboard-stat-card group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-[#718256] hover:shadow-md transition block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">فرص متاحة الآن</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 text-lg group-hover:scale-110 transition"><i class="fa-regular fa-eye" aria-hidden="true"></i></span>
                </div>
                <p class="mt-3 text-3xl font-black text-[#31421e]">{{ $availableCount }}</p>
                <span class="mt-1 block text-[11px] font-semibold text-[#718256]">طلبات تنتظر متطوعين</span>
            </a>

            {{-- طلبات هذا الأسبوع --}}
            <a href="{{ route('provider.tasks', ['tab' => 'all']) }}" class="dashboard-stat-card group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-400 hover:shadow-md transition block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">مهام هذا الأسبوع</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 text-lg group-hover:scale-110 transition"><i class="fa-regular fa-calendar" aria-hidden="true"></i></span>
                </div>
                <p class="mt-3 text-3xl font-black text-blue-900">{{ $thisWeekCount }}</p>
                <span class="mt-1 block text-[11px] font-semibold text-blue-600">نشاطك الأسبوعي المجدول</span>
            </a>

            {{-- خدمات مكتملة --}}
            <a href="{{ route('provider.tasks', ['tab' => 'completed']) }}" class="dashboard-stat-card group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">إجمالي الخدمات المكتملة</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 text-lg group-hover:scale-110 transition"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                </div>
                <p class="mt-3 text-3xl font-black text-emerald-800">{{ $completedCount }}</p>
                <span class="mt-1 block text-[11px] font-semibold text-emerald-600">خدمات تم تنفيذها بنجاح</span>
            </a>

            {{-- التقييم والمستوى --}}
            <a href="{{ route('provider.performance') }}" class="dashboard-stat-card group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-amber-400 hover:shadow-md transition block">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">التقييم والمستوى</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 text-lg group-hover:scale-110 transition"><i class="fa-solid fa-star" aria-hidden="true"></i></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-500">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs font-bold text-slate-400">من 5.0 ({{ $totalReviews }} تقييم)</span>
                </div>
                <span class="mt-1 block text-[11px] font-semibold text-amber-700">المستوى الحالي: Tier {{ $tier }}</span>
            </a>
        </div>

        {{-- 3. القسم الرئيسي: تفاصيل الطلب القادم + لوحة النشاط والوصول السريع --}}
        <div class="dashboard-main-grid grid gap-8 lg:grid-cols-[1.4fr_.6fr]">

            {{-- تفاصيل المهمة القادمة أو المجدولة الأقرب --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#31421e]">المهمة التالية</h2>
                        <p class="text-xs text-slate-500">تفاصيل الطلب الأقرب في جدولك وإجراءات التنفيذ</p>
                    </div>
                    <a href="{{ route('provider.tasks') }}" class="text-xs font-bold text-[#52643a] hover:underline">
                        عرض جميع طلباتي <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    </a>
                </div>

                @if ($nextTask)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6 hover:shadow-md transition">
                        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl shadow-sm">
                                    <x-service-icon :type="$nextTask->service_type" />
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-black text-slate-900">{{ $nextTask->service_type_label }}</h3>
                                        <span class="text-xs font-mono font-bold text-slate-400">{{ $nextTask->public_id }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $nextTask->scheduled_at->translatedFormat('l، d F Y - h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <span class="rounded-full px-3.5 py-1 text-xs font-bold border {{ $nextTask->status_badge_classes }}">
                                {{ $nextTask->status_label }}
                            </span>
                        </div>

                        {{-- الوصف --}}
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs text-slate-700 leading-6">
                            <p class="font-bold text-slate-500 mb-1">وصف الطلب والاحتياج:</p>
                            <p class="leading-relaxed">{{ $nextTask->description }}</p>
                        </div>

                        {{-- بيانات التواصل والموقع الدقيق بحسب صلاحيات الخصوصية --}}
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                                <span class="text-[11px] font-bold text-slate-400">المستفيد</span>
                                <p class="text-sm font-black text-[#31421e] mt-0.5">
                                    {{ $nextTask->elderProfile?->full_name ?? $nextTask->user?->name ?? 'طالب المساعدة' }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                                <span class="text-[11px] font-bold text-slate-400">العنوان المسجل</span>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ $nextTask->location }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5] sm:col-span-2">
                                <span class="text-[11px] font-bold text-slate-400">رقم الهاتف للتواصل المباشر</span>
                                <p class="text-sm font-bold text-slate-800 mt-0.5" dir="ltr">
                                    @if ($nextTask->canRevealContactPhone())
                                        {{ $nextTask->elderProfile?->phone_number ?? 'غير متوفر' }}
                                    @else
                                        <span class="text-xs text-amber-800 font-semibold" dir="rtl">
                                            <i class="fa-solid fa-lock" aria-hidden="true"></i> يظهر رقم الهاتف بعد قبول وتوكيل الطلب رسمياً
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- أزرار الإجراء السريع للمهمة --}}
                        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4">
                            @if ($nextTask->status === \App\Models\ServiceRequest::STATUS_ACCEPTED || $nextTask->status === \App\Models\ServiceRequest::STATUS_ASSIGNED || $nextTask->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                                <form method="POST" action="{{ route('provider.tasks.start-service', $nextTask) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-[#31421e] px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                                        <i class="fa-solid fa-bolt" aria-hidden="true"></i> بدء تقديم الخدمة
                                    </button>
                                </form>

                                <button type="button" onclick="openReportDelayModal('{{ route('provider.tasks.report-delay', $nextTask) }}')"
                                    class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                    <i class="fa-regular fa-clock" aria-hidden="true"></i> توقع تأخير
                                </button>
                            @elseif ($nextTask->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                                <button type="button" onclick="openFinishServiceModal('{{ route('provider.tasks.finish-service', $nextTask) }}')"
                                    class="rounded-2xl bg-emerald-700 px-6 py-2.5 text-xs font-bold text-white shadow-md hover:bg-emerald-800 transition cursor-pointer">
                                    <i class="fa-solid fa-check" aria-hidden="true"></i> إنهاء الخدمة وإرسال التأكيد
                                </button>
                            @endif

                            @if ($nextTask->canRevealContactPhone() && $nextTask->elderProfile?->phone_number)
                                <a href="tel:{{ $nextTask->elderProfile->phone_number }}"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                    <i class="fa-solid fa-phone" aria-hidden="true"></i> اتصال بالمستفيد
                                </a>
                            @endif

                            @if ($nextTask->canBeApologized())
                                <button type="button" onclick="openApologizeModal('{{ route('provider.tasks.apologize', $nextTask) }}')"
                                    class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                    الاعتذار عن المهمة
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                        <span class="text-4xl"><i class="fa-solid fa-mug-hot" aria-hidden="true"></i></span>
                        <h3 class="mt-3 text-base font-black text-slate-800">لا توجد مهام مجدولة قادمة</h3>
                        <p class="mt-1 text-xs text-slate-500">يمكنك استعراض الفرص التطوعية المتاحة الآن وقبول ما يناسب وقتك وقدراتك.</p>
                        <a href="{{ route('provider.available') }}"
                            class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                            <span>استعراض الطلبات المتاحة</span>
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- لوحة النشاط والوصول السريع --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-black text-[#31421e]">نشاط اليوم</h2>
                    <p class="text-xs text-slate-500">سجل التحديثات والإشعارات الأخيرة</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    @forelse ($recentTasks as $task)
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3 last:border-none last:pb-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#f8faf6] text-base border border-[#dfe6d5]">
                                <x-service-icon :type="$task->service_type" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $task->service_type_label }}</p>
                                <p class="text-[11px] text-slate-400">{{ $task->status_label }} • {{ $task->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">لا توجد أنشطة مسجلة مؤخراً.</p>
                    @endforelse

                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <a href="{{ route('provider.certificates') }}"
                            class="flex items-center justify-between rounded-2xl bg-[#f8faf6] p-3 text-xs font-bold text-[#31421e] hover:bg-[#eef2e8] transition border border-[#dfe6d5]">
                            <span class="flex items-center gap-2">
                                <span><i class="fa-solid fa-certificate" aria-hidden="true"></i></span>
                                <span>شهادات التطوع المعتمدة</span>
                            </span>
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('provider.availability') }}"
                            class="flex items-center justify-between rounded-2xl bg-[#f8faf6] p-3 text-xs font-bold text-[#31421e] hover:bg-[#eef2e8] transition border border-[#dfe6d5]">
                            <span class="flex items-center gap-2">
                                <span><i class="fa-solid fa-gears" aria-hidden="true"></i></span>
                                <span>إعدادات التوفر والتشغيل</span>
                            </span>
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-provider-layout>
