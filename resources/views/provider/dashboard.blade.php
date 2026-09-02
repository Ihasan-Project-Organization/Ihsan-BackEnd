<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- تنبيهات النظام --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✓</span>
                    <p class="text-sm font-bold">
                        {{ match(session('status')) {
                            'task-accepted' => 'تم قبول الطلب بنجاح ونقله إلى قائمة طلباتك.',
                            'task-dismissed' => 'تم تجاوز الطلب وإخفاؤه من القائمة دون التأثير على التقييم.',
                            'heading-started' => 'تم تسجيل بدء التوجه وإشعار كبير السن بأنك في الطريق.',
                            'arrival-confirmed' => 'تم تسجيل وصولك إلى الموقع بنجاح.',
                            'service-started' => 'تم بدء تقديم الخدمة بنجاح.',
                            'service-finished' => 'تم إرسال ملخص التنفيذ إلى كبير السن وبانتظار تأكيده.',
                            'delay-reported' => 'تم إرسال إشعار التأخير إلى كبير السن وتحديث الموعد المتوقع.',
                            'apology-completed' => 'تم تسجيل اعتذارك وفصل الإسناد وإعادة نشر الطلب بنجاح.',
                            'settings-updated' => 'تم حفظ جدول التوفر وإعدادات الخدمة بنجاح.',
                            default => 'تم تنفيذ العملية بنجاح.'
                        } }}
                    </p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-900">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <p class="text-sm font-bold">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-rose-600 hover:text-rose-900">✕</button>
            </div>
        @endif

        {{-- 1. بطاقة الترحيب والداشبورد الرئيسية (مستوحاة من صفحة 4 بالملف) --}}
        <section class="relative overflow-hidden rounded-3xl bg-[#31421e] p-6 text-white shadow-xl sm:p-8 lg:p-10">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-[#dfe6d5]">
                            <span>✨</span>
                            <span>مقدم خدمة معتمد</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-bold text-emerald-200">
                            <span>●</span>
                            <span>{{ $setting->is_available ? 'متاح لاستقبال المهام' : 'غير متاح مؤقتاً' }}</span>
                        </span>
                    </div>

                    <h1 class="mt-3 text-2xl font-black sm:text-3xl lg:text-4xl">مرحبًا {{ $provider->name }} 👋</h1>
                    <p class="mt-2 text-sm text-[#dfe6d5] sm:text-base">تابع طلباتك وخدماتك اليوم من مكان واحد بكل سهولة وتنظيم.</p>
                </div>

                {{-- بطاقة العطاء يبدأ بخطوة --}}
                @if ($nextTask)
                    <div class="rounded-2xl bg-white/10 p-5 backdrop-blur border border-white/15 max-w-md">
                        <div class="flex items-center justify-between gap-4">
                            <span class="rounded-xl bg-amber-400/20 px-3 py-1 text-xs font-black text-amber-300">
                                ⏳ موعدك القادم {{ $nextTask->scheduled_at->diffForHumans() }}
                            </span>
                            <span class="text-xs font-bold text-slate-200">{{ $nextTask->scheduled_at->format('h:i A') }}</span>
                        </div>
                        <h2 class="mt-2 text-base font-black text-white">العطاء يبدأ بخطوة</h2>
                        <p class="mt-1 text-xs leading-5 text-[#dfe6d5]">
                            لديك طلب <span class="font-bold text-white">"{{ $nextTask->service_type_label }}"</span> في {{ $nextTask->district ?? $nextTask->location }}، تأكد من بدء التوجه في الوقت المناسب.
                        </p>
                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('provider.tasks', ['tab' => 'upcoming']) }}" class="inline-flex items-center gap-1 rounded-xl bg-white px-4 py-2 text-xs font-bold text-[#31421e] hover:bg-[#eef2e8] transition">
                                <span>عرض تفاصيل الطلب</span>
                                <span>←</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl bg-white/10 p-5 backdrop-blur border border-white/15 max-w-md">
                        <span class="rounded-xl bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-200">لا توجد مهام حالية</span>
                        <h2 class="mt-2 text-base font-black text-white">جاهز لتقديم العون؟</h2>
                        <p class="mt-1 text-xs leading-5 text-[#dfe6d5]">يوجد حالياً <span class="font-bold text-white">{{ $availableCount }}</span> طلب متاح بانتظار متطوعين في منطقتك.</p>
                        <div class="mt-3">
                            <a href="{{ route('provider.available') }}" class="inline-flex items-center gap-1 rounded-xl bg-white px-4 py-2 text-xs font-bold text-[#31421e] hover:bg-[#eef2e8] transition">
                                <span>استعراض الطلبات المتاحة</span>
                                <span>←</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="absolute -bottom-20 -left-16 hidden h-72 w-72 rounded-full bg-[#718256]/30 lg:block"></div>
        </section>

        {{-- 2. بطاقات الإحصائيات الأربعة (صفحة 4) --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- طلبات متاحة --}}
            <a href="{{ route('provider.available') }}" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-[#718256] hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">طلبات متاحة</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 text-base group-hover:scale-110 transition">👁️</span>
                </div>
                <p class="mt-3 text-3xl font-black text-[#31421e]">{{ $availableCount }}</p>
                <span class="mt-1 block text-xs font-semibold text-[#718256]">فرص مساعدة متوافقة معك</span>
            </a>

            {{-- طلبات هذا الأسبوع --}}
            <a href="{{ route('provider.tasks', ['tab' => 'all']) }}" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-blue-400 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">طلبات هذا الأسبوع</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 text-base group-hover:scale-110 transition">📅</span>
                </div>
                <p class="mt-3 text-3xl font-black text-blue-900">{{ $thisWeekCount }}</p>
                <span class="mt-1 block text-xs font-semibold text-blue-600">مهام قمت بجدولتها وتنفيذها</span>
            </a>

            {{-- خدمة مكتملة --}}
            <a href="{{ route('provider.tasks', ['tab' => 'completed']) }}" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">خدمة مكتملة</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 text-base group-hover:scale-110 transition">✓</span>
                </div>
                <p class="mt-3 text-3xl font-black text-emerald-800">{{ $completedCount }}</p>
                <span class="mt-1 block text-xs font-semibold text-emerald-600">إجمالي المساعدات المنجزة</span>
            </a>

            {{-- متوسط التقييم --}}
            <a href="{{ route('provider.performance') }}" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-amber-400 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">متوسط التقييم</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 text-base group-hover:scale-110 transition">★</span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-500">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs font-bold text-slate-400">من 5.0 ({{ $totalReviews }} تقييم)</span>
                </div>
                <span class="mt-1 block text-xs font-semibold text-amber-600">تقييمات كبار السن</span>
            </a>
        </div>

        {{-- 3. القسم الأوسط: طلبك القادم + نشاط اليوم --}}
        <div class="grid gap-8 lg:grid-cols-[1.4fr_.6fr]">

            {{-- طلبك القادم بالتفصيل --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#31421e]">طلبك القادم</h2>
                        <p class="text-xs text-slate-500">تفاصيل المهمة التالية المجدولة لك</p>
                    </div>
                    <a href="{{ route('provider.tasks') }}" class="text-xs font-bold text-[#52643a] hover:underline">عرض كل طلباتي ←</a>
                </div>

                @if ($nextTask)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl">
                                    {{ $nextTask->service_type_icon }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-black text-slate-900">{{ $nextTask->service_type_label }}</h3>
                                        <span class="text-xs font-bold text-slate-400">{{ $nextTask->public_id }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        📍 {{ $nextTask->location }} • 🕒 {{ $nextTask->scheduled_at->format('Y-m-d h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <span class="rounded-full px-3.5 py-1 text-xs font-bold border {{ $nextTask->status_badge_classes }}">
                                {{ $nextTask->status_label }}
                            </span>
                        </div>

                        {{-- الوصف --}}
                        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs text-slate-700 leading-6">
                            <p class="font-bold text-slate-500 mb-1">وصف المهمة:</p>
                            <p>{{ $nextTask->description }}</p>
                        </div>

                        {{-- بيانات التواصل والموقع الدقيق --}}
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                                <span class="text-[11px] font-bold text-slate-400">كبير السن المستفيد</span>
                                <p class="text-sm font-black text-[#31421e] mt-0.5">{{ $nextTask->user->name }}</p>
                            </div>
                            <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                                <span class="text-[11px] font-bold text-slate-400">رقم الهاتف للتواصل</span>
                                <p class="text-sm font-bold text-slate-800 mt-0.5" dir="ltr">{{ $nextTask->user->registrationProfile?->phone ?? '0599000000' }}</p>
                            </div>
                        </div>

                        {{-- أزرار الإجراء السريع للمهمة --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            @if ($nextTask->status === \App\Models\ServiceRequest::STATUS_ACCEPTED || $nextTask->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                                <form method="POST" action="{{ route('provider.tasks.start-heading', $nextTask) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                                        🚗 ابدأ التوجه الآن
                                    </button>
                                </form>
                            @elseif ($nextTask->status === \App\Models\ServiceRequest::STATUS_ON_THE_WAY)
                                <form method="POST" action="{{ route('provider.tasks.confirm-arrival', $nextTask) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-teal-700 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-teal-800 transition cursor-pointer">
                                        📍 تأكيد الوصول للموقع
                                    </button>
                                </form>
                            @elseif ($nextTask->status === \App\Models\ServiceRequest::STATUS_ARRIVED)
                                <form method="POST" action="{{ route('provider.tasks.start-service', $nextTask) }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-purple-700 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-purple-800 transition cursor-pointer">
                                        ⚡ بدء تقديم الخدمة
                                    </button>
                                </form>
                            @elseif ($nextTask->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                                <button type="button" onclick="openFinishServiceModal('{{ route('provider.tasks.finish-service', $nextTask) }}')"
                                    class="rounded-2xl bg-emerald-700 px-6 py-3 text-xs font-bold text-white shadow-md hover:bg-emerald-800 transition cursor-pointer">
                                    ✓ إنهاء الخدمة
                                </button>
                            @endif

                            <a href="tel:{{ $nextTask->user->registrationProfile?->phone ?? '0599000000' }}"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                📞 اتصال بالمستفيد
                            </a>

                            @if ($nextTask->canBeApologized())
                                <button type="button" onclick="openApologizeModal('{{ route('provider.tasks.apologize', $nextTask) }}')"
                                    class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                    الاعتذار
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                        <span class="text-4xl">☕</span>
                        <h3 class="mt-3 text-base font-black text-slate-800">لا توجد مهام مجدولة قادمة</h3>
                        <p class="mt-1 text-xs text-slate-500">يمكنك استعراض الفرص التطوعية المتاحة الآن وقبول ما يناسبك.</p>
                        <a href="{{ route('provider.available') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                            <span>استعراض الطلبات المتاحة</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- نشاط اليوم والإعدادات السريعة --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-black text-[#31421e]">نشاط اليوم</h2>
                    <p class="text-xs text-slate-500">سجل التحديثات والإشعارات الأخيرة</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    @forelse ($recentTasks as $task)
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-3 last:border-none last:pb-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-sm">
                                {{ $task->service_type_icon }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $task->service_type_label }}</p>
                                <p class="text-[11px] text-slate-400">{{ $task->status_label }} • {{ $task->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">لا توجد أنشطة مسجلة اليوم حتى الآن.</p>
                    @endforelse

                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('provider.availability') }}" class="flex items-center justify-between rounded-2xl bg-[#eef2e8] p-3 text-xs font-bold text-[#31421e] hover:bg-[#dfe6d5] transition">
                            <span>⚙️ تعديل جدول التوفر والمناطق</span>
                            <span>←</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('provider.partials.modals')
</x-app-layout>

