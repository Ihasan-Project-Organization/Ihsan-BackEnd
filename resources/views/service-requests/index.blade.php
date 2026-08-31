<x-app-layout>
    <div x-data class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">

        {{-- تنبيهات الحالة والعمليات --}}
        @if (session('status') === 'request-created')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>تم إنشاء ونشر طلب المساعدة بنجاح! سيتم إشعارك فور قبول أحد مقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-rescheduled')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>تم تحديد موعد جديد وإعادة نشر الطلب بنجاح بنفس الرقم وتاريخ المحاولات.</span>
            </div>
        @elseif (session('status') === 'request-updated')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>تم تعديل بيانات الطلب وإعادة نشره لمقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-completed')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>تم تأكيد اكتمال الخدمة بنجاح! شكرًا لك، يمكنك الآن تقييم مقدم الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-cancelled')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-100 p-4 text-sm font-bold text-slate-700 shadow-sm">
                <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>تم إلغاء الطلب ونقله إلى قسم الطلبات الملغاة.</span>
            </div>
        @elseif (session('status') === 'request-reassigned')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm font-bold text-blue-800 shadow-sm">
                <svg class="h-5 w-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>تم فك الإسناد وإعادة نشر الطلب للبحث عن مقدم خدمة بديل.</span>
            </div>
        @elseif (session('status') === 'review-submitted')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-bold text-amber-800 shadow-sm">
                <svg class="h-5 w-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>شكرًا لك! تم إرسال تقييمك لمقدم الخدمة بنجاح.</span>
            </div>
        @endif

        {{-- الرأس العلوي مع البحث وزر طلب جديد --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
            <div>
                <p class="text-xs font-bold text-[#718256]">لوحة التحكم</p>
                <h1 class="text-2xl sm:text-3xl font-black text-[#31421e]">طلباتي</h1>
                <p class="mt-1 text-xs text-slate-500">تابع/ي حالة جميع طلبات المساعدة والمرافقة</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
                {{-- شريط البحث --}}
                <form method="GET" action="{{ route('service-requests.index') }}" class="relative w-full sm:w-auto sm:min-w-[240px]">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="ابحث عن أي شيء..."
                        class="w-full rounded-xl border-slate-200 bg-white py-2.5 pr-10 pl-4 text-xs shadow-sm focus:border-[#718256] focus:ring-[#718256]">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </form>

                {{-- زر طلب جديد كما في التصميم --}}
                <button type="button"
                    onclick="openCreateRequestModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#31421e] px-5 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition duration-200 cursor-pointer">
                    <span class="text-base leading-none">+</span>
                    <span>طلب جديد</span>
                </button>
            </div>
        </div>

        {{-- شريط التبويبات الـ 4 الرئيسي --}}
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-6 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('service-requests.index', ['tab' => 'all', 'search' => $search]) }}"
                class="rounded-xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'all' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                الكل ({{ $counts['all'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'active', 'search' => $search]) }}"
                class="rounded-xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'active' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                نشطة ({{ $counts['active'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'needs_action', 'search' => $search]) }}"
                class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'needs_action' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>بحاجة إلى إجراء</span>
                @if ($counts['needs_action'] > 0)
                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-[10px] font-black text-white shadow-sm">
                        {{ $counts['needs_action'] }}
                    </span>
                @endif
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'completed', 'search' => $search]) }}"
                class="rounded-xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'completed' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                مكتملة ({{ $counts['completed'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'cancelled', 'search' => $search]) }}"
                class="rounded-xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'cancelled' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                ملغاة ({{ $counts['cancelled'] }})
            </a>
        </div>

        {{-- قائمة بطاقات الطلبات --}}
        <div class="space-y-4">
            @forelse ($requests as $item)
                <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md sm:p-6">
                    {{-- رقم الطلب في الزاوية العلوية كما في الصور --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            {{-- الأيقونة الملونة بحسب نوع وحالة الطلب --}}
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl
                                @if (in_array($item->status, ['no_provider_found', 'provider_delayed', 'provider_apologized']))
                                    bg-amber-50 text-amber-600 border border-amber-200
                                @elseif ($item->status === 'completed')
                                    bg-emerald-50 text-emerald-600 border border-emerald-200
                                @elseif ($item->status === 'cancelled')
                                    bg-slate-100 text-slate-400
                                @else
                                    bg-[#eef2e8] text-[#31421e] border border-[#dfe6d5]
                                @endif
                            ">
                                @if (str_contains($item->title, 'دواء') || str_contains($item->title, 'صيدلية'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                @elseif (str_contains($item->title, 'أغراض') || str_contains($item->title, 'شراء'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                @elseif (str_contains($item->title, 'مرافقة') || str_contains($item->title, 'مستشفى'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                @elseif (str_contains($item->title, 'زيارة') || str_contains($item->title, 'اجتماعية'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                @endif
                            </div>

                            {{-- تفاصيل الطلب --}}
                            <div>
                                <h2 class="text-base font-extrabold text-slate-800">{{ $item->title }}</h2>
                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item->description }}</p>

                                {{-- بيانات الموعد ومقدم الخدمة والموقع --}}
                                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $item->scheduled_at->translatedFormat('d F h:i A') }}</span>
                                    </span>

                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        <span>{{ $item->location }}</span>
                                    </span>

                                    @if ($item->assignedProvider)
                                        <span class="flex items-center gap-1.5 text-[#31421e] font-bold">
                                            <svg class="h-4 w-4 text-[#718256]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>مقدم الخدمة: {{ $item->assignedProvider->name }}</span>
                                        </span>
                                    @endif

                                    @if ($item->attempts_count > 1)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-bold text-slate-500">
                                            المحاولة رقم: {{ $item->attempts_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- رقم الطلب --}}
                        <span class="text-xs font-mono font-bold text-slate-400">{{ $item->public_id }}</span>
                    </div>

                    {{-- قسم الحالة والأزرار التفاعلية المخصصة لكل حالة (طبقاً للـ 5 مسارات في الملفين) --}}
                    <div class="mt-5 border-t border-slate-100 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        {{-- 1. حالة بانتظار قبول مقدم خدمة --}}
                        @if ($item->status === 'pending_acceptance')
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>بانتظار قبول مقدم خدمة — طلبك منشور حاليًا لمقدمي الخدمة المناسبين.</span>
                            </div>
                            <button type="button"
                                onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                class="text-xs font-bold text-slate-400 hover:text-red-600 transition cursor-pointer">
                                إلغاء الطلب
                            </button>

                        {{-- 2. حالة تم قبول الطلب --}}
                        @elseif ($item->status === 'accepted')
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-700">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>تم قبول الطلب — تم ربط مقدم الخدمة بطلبك، وسيتوجه إليك في الموعد المحدد.</span>
                            </div>
                            <div class="flex items-center gap-3">
                                @if ($item->assignedProvider)
                                    <button type="button"
                                        onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '') }}')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                        <span>📞 تواصل معه</span>
                                    </button>
                                @endif
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="text-xs font-bold text-slate-400 hover:text-red-600 transition cursor-pointer">
                                    إلغاء الطلب
                                </button>
                            </div>

                        {{-- 3. حالة قيد التنفيذ --}}
                        @elseif ($item->status === 'in_progress')
                            <div class="flex items-center gap-2 text-xs font-bold text-blue-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500 animate-ping"></span>
                                <span>قيد التنفيذ — الخدمة جارية الآن وسيظهر التأكيد فور إنهائها.</span>
                            </div>
                            @if ($item->assignedProvider)
                                <button type="button"
                                    onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '') }}')"
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    <span>📞 تواصل معه</span>
                                </button>
                            @endif

                        {{-- 4. حالة بانتظار تأكيد كبير السن --}}
                        @elseif ($item->status === 'pending_confirmation')
                            <div class="flex items-center gap-2 text-xs font-bold text-blue-800">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>بانتظار تأكيدك — أنهى مقدم الخدمة المهمة وينتظر تأكيدك.</span>
                            </div>
                            <form method="POST" action="{{ route('service-requests.confirm', $item) }}">
                                @csrf
                                @method('patch')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                                    <span>✓ تأكيد اكتمال الخدمة</span>
                                </button>
                            </form>

                        {{-- 5. حالة تم تنفيذ الطلب (المكتملة) --}}
                        @elseif ($item->status === 'completed')
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-800">
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>تم تنفيذ الطلب — انتقل الطلب إلى المكتملة وأصبح التقييم متاحًا.</span>
                            </div>
                            <div>
                                @if ($item->review)
                                    <div class="flex items-center gap-1 text-amber-400 font-bold text-xs bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                                        <span>تقييمك:</span>
                                        <span>{{ str_repeat('★', $item->review->rating) }}</span>
                                    </div>
                                @else
                                    <button type="button"
                                        onclick="openReviewModal({{ $item->id }}, '{{ addslashes($item->assignedProvider?->name ?? 'المتطوع') }}', '{{ route('service-requests.reviews.store', $item) }}')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                        <span>⭐ تقييم الخدمة</span>
                                    </button>
                                @endif
                            </div>

                        {{-- 6. حالات بحاجة إلى إجراء: لم يتم العثور على مقدم خدمة --}}
                        @elseif ($item->status === 'no_provider_found')
                            <div class="flex items-center gap-2 text-xs font-bold text-red-700">
                                <span class="flex h-2 w-2 rounded-full bg-red-600"></span>
                                <span>لم يتم العثور على مقدم خدمة — وصل موعد الطلب دون أن يقبله أحد. اختر الإجراء المناسب.</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button"
                                    onclick="openRescheduleModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.reschedule', $item) }}')"
                                    class="rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer">
                                    🔄 موعد جديد وإعادة النشر
                                </button>
                                <button type="button"
                                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', '{{ addslashes($item->location) }}', '{{ $item->scheduled_at->format('Y-m-d\TH:i') }}', '{{ route('service-requests.update', $item) }}')"
                                    class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📝 تعديل وإعادة النشر
                                </button>
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition cursor-pointer">
                                    🚫 إلغاء الطلب
                                </button>
                            </div>

                        {{-- 7. حالات بحاجة إلى إجراء: اعتذر مقدم الخدمة --}}
                        @elseif ($item->status === 'provider_apologized')
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                <span class="flex h-2 w-2 rounded-full bg-amber-500"></span>
                                <span>اعتذر مقدم الخدمة — يمكنك تحديد موعد جديد والبحث عن مقدم خدمة آخر.</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button"
                                    onclick="openRescheduleModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.reschedule', $item) }}')"
                                    class="rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer">
                                    🔄 موعد جديد وإعادة النشر
                                </button>
                                <button type="button"
                                    onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->description) }}', '{{ addslashes($item->location) }}', '{{ $item->scheduled_at->format('Y-m-d\TH:i') }}', '{{ route('service-requests.update', $item) }}')"
                                    class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📝 تعديل وإعادة النشر
                                </button>
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition cursor-pointer">
                                    🚫 إلغاء الطلب
                                </button>
                            </div>

                        {{-- 8. حالات بحاجة إلى إجراء: مقدم الخدمة متأخر --}}
                        @elseif ($item->status === 'provider_delayed')
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                                <span>مقدم الخدمة متأخر — انتهت مهلة الوصول التي حددها النظام.</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('service-requests.search-alternative', $item) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit"
                                        class="rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer">
                                        🔍 البحث عن بديل
                                    </button>
                                </form>
                                @if ($item->assignedProvider)
                                    <button type="button"
                                        onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '') }}')"
                                        class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                        📞 تواصل معه
                                    </button>
                                @endif
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition cursor-pointer">
                                    🚫 إلغاء
                                </button>
                            </div>

                        {{-- 9. حالة الطلب الملغى --}}
                        @elseif ($item->status === 'cancelled')
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                <span>تم إلغاء الطلب</span>
                                @if ($item->cancellation_reason)
                                    <span class="text-slate-400 font-normal">({{ $item->cancellation_reason }})</span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-slate-400">
                                ألغي في: {{ $item->cancelled_at?->translatedFormat('d M Y') ?? $item->updated_at->translatedFormat('d M Y') }}
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                {{-- الحالة الفارغة --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-[#f8faf6] text-[#31421e]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-black text-slate-800">لا توجد طلبات في هذا القسم</h3>
                    <p class="mt-1 text-xs text-slate-500">يمكنك إنشاء طلب مساعدة جديد في أي وقت وسيتولى المتطوعون تقديم يد العون.</p>
                    <button type="button" onclick="openCreateRequestModal()"
                        class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-[#52643a] transition cursor-pointer">
                        <span>+ إنشاء طلب مساعدة جديد</span>
                    </button>
                </div>
            @endforelse

            {{-- الترقيم الصفحي --}}
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
