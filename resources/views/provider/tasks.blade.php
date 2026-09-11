<x-provider-layout>
    <div class="space-y-6">

        {{-- رأس الصفحة --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">طلباتي</h1>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">إدارة المهام الموكلة إليك ومتابعة مراحل التنفيذ وإغلاق الخدمات.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('provider.available') }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                    <span>+ تصفح الطلبات المتاحة</span>
                </a>
            </div>
        </div>

        {{-- تنبيهات --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span>✓ {{ match(session('status')) {
                    'task-accepted' => 'تم قبول الطلب بنجاح وإسناده لك.',
                    'service-started' => 'تم بدء تقديم الخدمة بنجاح.',
                    'service-finished' => 'تم إرسال ملخص التنفيذ إلى كبير السن وبانتظار تأكيده.',
                    'delay-reported' => 'تم تسجيل إشعار التأخير بنجاح.',
                    'apology-completed' => 'تم تسجيل اعتذارك وفصل الإسناد بنجاح.',
                    'elder-rated' => 'تم حفظ تقييمك للمستفيد لدى الإدارة بنجاح.',
                    default => 'تم تنفيذ الإجراء بنجاح.'
                } }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span>⚠️ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-rose-600 hover:text-rose-900 cursor-pointer">✕</button>
            </div>
        @endif

        {{-- شريط التبويبات الرئيسي --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
            <a href="{{ route('provider.tasks', ['tab' => 'all', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'all' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                الكل ({{ $counts['all'] }})
            </a>
            <a href="{{ route('provider.tasks', ['tab' => 'upcoming', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'upcoming' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                القادمة والمجدولة ({{ $counts['upcoming'] }})
            </a>
            <a href="{{ route('provider.tasks', ['tab' => 'in_progress', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'in_progress' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                قيد التنفيذ ({{ $counts['in_progress'] }})
            </a>
            <a href="{{ route('provider.tasks', ['tab' => 'pending_confirmation', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'pending_confirmation' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                بانتظار التأكيد ({{ $counts['pending_confirmation'] }})
            </a>
            <a href="{{ route('provider.tasks', ['tab' => 'completed', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'completed' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                المكتملة ({{ $counts['completed'] }})
            </a>
        </div>

        {{-- قائمة الطلبات --}}
        @if ($requests->count() > 0)
            <div class="space-y-6">
                @foreach ($requests as $req)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-slate-300 transition space-y-6">

                        {{-- رأس البطاقة: نوع الخدمة والمعرف والحالة --}}
                        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl shadow-sm">
                                    {{ $req->service_type_icon }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-black text-slate-900">{{ $req->service_type_label }}</h3>
                                        <span class="text-xs font-mono font-bold text-slate-400">{{ $req->public_id }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        📍 <span class="font-bold text-slate-700">{{ $req->location }}</span> • 🕒 {{ $req->scheduled_at->translatedFormat('l، d F Y - h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="rounded-full px-3.5 py-1 text-xs font-bold border {{ $req->status_badge_classes }}">
                                    {{ $req->status_label }}
                                </span>
                            </div>
                        </div>

                        {{-- المخطط المرحلي المتسلسل الدقيق (الستيبر: 5 مراحل معتمدة) --}}
                        @php($step = $req->step_index)
                        <div class="hidden sm:block">
                            <div class="relative flex items-center justify-between">
                                <div class="absolute right-0 top-1/2 -z-0 h-1 w-full -translate-y-1/2 bg-slate-100 rounded-full"></div>
                                <div class="absolute right-0 top-1/2 -z-0 h-1 -translate-y-1/2 bg-[#52643a] transition-all duration-500 rounded-full"
                                    style="width: {{ $step >= 5 ? '100%' : (($step - 1) / 4 * 100) . '%' }}"></div>

                                @php($steps = [
                                    1 => 'تم النشر',
                                    2 => 'تم القبول والتوكيل',
                                    3 => 'قيد التنفيذ',
                                    4 => 'بانتظار التأكيد',
                                    5 => 'مكتمل وموثق',
                                ])

                                @foreach ($steps as $idx => $label)
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-black transition {{ $step > $idx ? 'bg-[#31421e] text-white shadow-sm' : ($step === $idx ? 'bg-[#718256] text-white ring-4 ring-[#eef2e8]' : 'bg-slate-100 text-slate-400') }}">
                                            @if ($step > $idx)
                                                ✓
                                            @else
                                                {{ $idx }}
                                            @endif
                                        </div>
                                        <span class="mt-2 text-[11px] font-bold {{ $step >= $idx ? 'text-[#31421e]' : 'text-slate-400' }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- تفاصيل الطلب وبيانات المستفيد --}}
                        <div class="grid gap-4 md:grid-cols-[1.5fr_1fr]">
                            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs text-slate-700 leading-6">
                                <p class="font-bold text-slate-500 mb-1">وصف الطلب والاحتياج:</p>
                                <p class="leading-relaxed">{{ $req->description }}</p>
                            </div>

                            <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5] space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">كبير السن:</span>
                                    <span class="font-black text-[#31421e]">{{ $req->elderProfile?->full_name ?? $req->user?->name ?? 'كبير السن' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">العنوان الدقيق:</span>
                                    <span class="font-bold text-slate-800">{{ $req->location }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">رقم الهاتف:</span>
                                    <span class="font-bold text-slate-800" dir="ltr">
                                        @if ($req->canRevealContactPhone())
                                            {{ $req->elderProfile?->phone_number ?? 'غير متوفر' }}
                                        @else
                                            <span class="text-[11px] text-amber-800 font-semibold" dir="rtl">يظهر بعد القبول والتوكيل</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- إذا تم الإبلاغ عن تأخير --}}
                        @if ($req->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                            <div class="rounded-2xl bg-amber-50 p-4 border border-amber-200 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-amber-900">
                                    <span class="text-base">⏳</span>
                                    <div>
                                        <span class="font-bold">تم إشعار كبير السن بالتأخير.</span>
                                        <p class="text-[11px] text-amber-700 mt-0.5">يرجى المبادرة ببدء تقديم الخدمة فور وصولك.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- إذا وجد تقييم من كبير السن --}}
                        @if ($req->review)
                            <div class="rounded-2xl bg-amber-50/70 p-4 border border-amber-200/80 flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-amber-900">تقييم ورأي كبير السن:</span>
                                    <p class="mt-1 text-xs text-slate-700 font-semibold leading-relaxed">"{{ $req->review->comment ?? 'خدمة ممتازة، بارك الله فيكم.' }}"</p>
                                </div>
                                <div class="flex items-center gap-1 text-amber-500 font-bold text-sm shrink-0">
                                    <span>{{ $req->review->stars }}</span>
                                    <span>★</span>
                                </div>
                            </div>
                        @endif

                        {{-- أزرار وسير العمليات التفاعلية المعتمدة --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- 1. حالة تم القبول أو متأخر: زر بدء الخدمة وتوقع تأخير --}}
                                @if (in_array($req->status, [\App\Models\ServiceRequest::STATUS_ACCEPTED, \App\Models\ServiceRequest::STATUS_ASSIGNED, \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED]))
                                    <form method="POST" action="{{ route('provider.tasks.start-service', $req) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                                            ⚡ بدء تقديم الخدمة
                                        </button>
                                    </form>

                                    <button type="button" onclick="openReportDelayModal('{{ route('provider.tasks.report-delay', $req) }}')"
                                        class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                        ⏳ توقع تأخير
                                    </button>
                                @endif

                                {{-- 2. حالة قيد التنفيذ: زر إنهاء الخدمة --}}
                                @if ($req->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                                    <button type="button" onclick="openFinishServiceModal('{{ route('provider.tasks.finish-service', $req) }}')"
                                        class="rounded-2xl bg-emerald-700 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-800 transition cursor-pointer">
                                        ✓ إنهاء الخدمة وإرسال التأكيد
                                    </button>
                                @endif

                                {{-- تواصل مع المستفيد عند توفر الشروط --}}
                                @php($elderPhone = $req->elderProfile?->phone_number)
                                @if ($req->canRevealContactPhone() && $elderPhone)
                                    <a href="tel:{{ $elderPhone }}"
                                        class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                        📞 تواصل مع المستفيد (<span dir="ltr">{{ $elderPhone }}</span>)
                                    </a>
                                @endif

                                {{-- تقييم اختياري لكبير السن بعد إنهاء الخدمة --}}
                                @if (in_array($req->status, [\App\Models\ServiceRequest::STATUS_COMPLETED, \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION], true))
                                    @php($providerReview = $req->ratings()->where('rater_role', 'provider')->first())
                                    @if ($providerReview)
                                        <span class="rounded-2xl bg-amber-50 border border-amber-200 px-3 py-2 text-xs font-bold text-amber-800">
                                            ⭐ تقييمك للمستفيد: {{ $providerReview->stars }} / 5
                                        </span>
                                    @else
                                        <button type="button" onclick="openRateElderModal('{{ route('provider.tasks.rate-elder', $req) }}')"
                                            class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                            ⭐ تقييم المستفيد (للإدارة)
                                        </button>
                                    @endif
                                @endif
                            </div>

                            {{-- زر الاعتذار --}}
                            @if ($req->canBeApologized())
                                <button type="button" onclick="openApologizeModal('{{ route('provider.tasks.apologize', $req) }}')"
                                    class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                    الاعتذار عن الطلب
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm space-y-3">
                <span class="text-4xl">📋</span>
                <h3 class="text-base font-black text-slate-800">لا توجد طلبات في هذا التبويب</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                    يمكنك تصفح الفرص المتاحة وقبول طلبات جديدة لتقديم العون لكبار السن.
                </p>
                <div class="pt-2">
                    <a href="{{ route('provider.available') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                        <span>استعراض الطلبات المتاحة</span>
                        <span>←</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-provider-layout>
