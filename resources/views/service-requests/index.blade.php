<x-app-layout>
    <div x-data class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">

        {{-- تنبيهات الحالة والعمليات --}}
        @if (session('status') === 'request-created')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <span class="text-lg">✓</span>
                <span>تم إنشاء ونشر طلب المساعدة بنجاح! سيتم إشعارك فور قبول أحد مقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-rescheduled')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <span class="text-lg">🔄</span>
                <span>تم تحديد موعد جديد وإعادة نشر الطلب بنجاح بنفس الرقم وتاريخ المحاولات.</span>
            </div>
        @elseif (session('status') === 'request-updated')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <span class="text-lg">📝</span>
                <span>تم تعديل بيانات الطلب وإعادة نشره لمقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-completed')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <span class="text-lg">✓</span>
                <span>تم تأكيد اكتمال الخدمة بنجاح! شكرًا لك، يمكنك الآن تقييم مقدم الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-cancelled')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-100 p-4 text-sm font-bold text-slate-700 shadow-sm">
                <span class="text-lg">🚫</span>
                <span>تم إلغاء الطلب ونقله إلى قسم الطلبات الملغاة.</span>
            </div>
        @elseif (session('status') === 'request-reassigned')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm font-bold text-blue-800 shadow-sm">
                <span class="text-lg">🔍</span>
                <span>تم فك الإسناد وإعادة نشر الطلب للبحث عن مقدم خدمة بديل.</span>
            </div>
        @elseif (session('status') === 'review-submitted')
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-bold text-amber-800 shadow-sm">
                <span class="text-lg">⭐</span>
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
                        placeholder="ابحث برقم الطلب أو العنوان..."
                        class="w-full rounded-2xl border-slate-200 bg-white py-2.5 pr-10 pl-4 text-xs shadow-sm focus:border-[#718256] focus:ring-[#718256]">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </form>

                {{-- زر طلب جديد --}}
                <button type="button"
                    onclick="openCreateRequestModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition duration-200 cursor-pointer">
                    <span class="text-base leading-none">+</span>
                    <span>طلب جديد</span>
                </button>
            </div>
        </div>

        {{-- شريط التبويبات الرئيسي --}}
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-6 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('service-requests.index', ['tab' => 'all', 'search' => $search]) }}"
                class="rounded-2xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'all' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                الكل ({{ $counts['all'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'active', 'search' => $search]) }}"
                class="rounded-2xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'active' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                نشطة ({{ $counts['active'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'needs_action', 'search' => $search]) }}"
                class="inline-flex items-center gap-2 rounded-2xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'needs_action' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>بحاجة إلى إجراء</span>
                @if ($counts['needs_action'] > 0)
                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-[10px] font-black text-white shadow-sm">
                        {{ $counts['needs_action'] }}
                    </span>
                @endif
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'completed', 'search' => $search]) }}"
                class="rounded-2xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'completed' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                مكتملة ({{ $counts['completed'] }})
            </a>

            <a href="{{ route('service-requests.index', ['tab' => 'cancelled', 'search' => $search]) }}"
                class="rounded-2xl px-3.5 py-2 text-xs font-extrabold transition shrink-0 {{ $tab === 'cancelled' ? 'bg-[#31421e] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                ملغاة ({{ $counts['cancelled'] }})
            </a>
        </div>

        {{-- قائمة بطاقات الطلبات --}}
        <div class="space-y-5">
            @forelse ($requests as $item)
                <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md sm:p-6 space-y-4">
                    
                    {{-- رأس البطاقة --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl">
                                {{ $item->service_type_icon }}
                            </span>

                            <div>
                                <h2 class="text-base font-black text-slate-800">{{ $item->title }}</h2>
                                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item->description }}</p>

                                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600">
                                    <span class="flex items-center gap-1">
                                        <span>🕒</span>
                                        <span>{{ $item->scheduled_at->translatedFormat('d F h:i A') }}</span>
                                    </span>

                                    <span class="flex items-center gap-1">
                                        <span>📍</span>
                                        <span>{{ $item->location }}</span>
                                    </span>

                                    @if ($item->assignedProvider)
                                        <span class="flex items-center gap-1 text-[#31421e] font-bold">
                                            <span>🤝</span>
                                            <span>مقدم الخدمة: {{ $item->assignedProvider->name }}</span>
                                        </span>
                                    @endif

                                    @if ($item->attempts_count > 1)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-500">
                                            المحاولة رقم {{ $item->attempts_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <span class="text-xs font-mono font-bold text-slate-400">{{ $item->public_id }}</span>
                    </div>

                    {{-- قسم الحالة والأزرار التفاعلية المخصصة لكل حالة --}}
                    <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        {{-- 1. حالة بانتظار قبول مقدم خدمة --}}
                        @if ($item->status === \App\Models\ServiceRequest::STATUS_PENDING_ACCEPTANCE)
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>طلب متاح — منشور لمقدمي الخدمة وبانتظار القبول.</span>
                            </div>
                            <button type="button"
                                onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                class="text-xs font-bold text-slate-400 hover:text-red-600 transition cursor-pointer">
                                إلغاء الطلب
                            </button>

                        {{-- 2. حالة تم قبول الطلب --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_ACCEPTED)
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-700">
                                <span>✓</span>
                                <span>تم قبول الطلب — تم إسناد الطلب لمقدم الخدمة وسيتوجه في الموعد.</span>
                            </div>
                            @if ($item->assignedProvider)
                                <button type="button"
                                    onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '0599000000') }}')"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📞 تواصل معه
                                </button>
                            @endif

                        {{-- 3. حالة في الطريق --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_ON_THE_WAY)
                            <div class="flex items-center gap-2 text-xs font-bold text-indigo-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-indigo-500 animate-ping"></span>
                                <span>مقدم الخدمة في الطريق إليك الآن.</span>
                            </div>
                            @if ($item->assignedProvider)
                                <button type="button"
                                    onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '0599000000') }}')"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📞 تواصل معه
                                </button>
                            @endif

                        {{-- 4. حالة وصل إلى الموقع --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_ARRIVED)
                            <div class="flex items-center gap-2 text-xs font-bold text-teal-700">
                                <span>📍</span>
                                <span>وصل مقدم الخدمة إلى موقعك وسيتم بدء الخدمة.</span>
                            </div>
                            @if ($item->assignedProvider)
                                <button type="button"
                                    onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '0599000000') }}')"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📞 تواصل معه
                                </button>
                            @endif

                        {{-- 5. حالة قيد التنفيذ --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                            <div class="flex items-center gap-2 text-xs font-bold text-purple-700">
                                <span class="h-2.5 w-2.5 rounded-full bg-purple-500 animate-ping"></span>
                                <span>قيد التنفيذ — تقديم الخدمة جارٍ الآن.</span>
                            </div>
                            @if ($item->assignedProvider)
                                <button type="button"
                                    onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '0599000000') }}')"
                                    class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                    📞 تواصل معه
                                </button>
                            @endif

                        {{-- 6. حالة بانتظار تأكيد كبير السن --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION)
                            <div class="flex items-center gap-2 text-xs font-bold text-orange-800">
                                <span>⏳</span>
                                <span>أنهى مقدم الخدمة المهمة وبانتظار تأكيدك لإغلاق الطلب وتقييم الخدمة.</span>
                            </div>
                            <form method="POST" action="{{ route('service-requests.confirm', $item) }}">
                                @csrf
                                @method('patch')
                                <button type="submit"
                                    class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                                    ✓ تأكيد اكتمال الخدمة
                                </button>
                            </form>

                        {{-- 7. حالة تم تنفيذ الطلب (المكتملة) --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_COMPLETED)
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-800">
                                <span>✓</span>
                                <span>تم تنفيذ الطلب بنجاح.</span>
                            </div>
                            <div>
                                @if ($item->review)
                                    <div class="flex items-center gap-1 text-amber-500 font-bold text-xs bg-amber-50 px-3 py-1.5 rounded-2xl border border-amber-200">
                                        <span>تقييمك:</span>
                                        <span>{{ str_repeat('★', $item->review->rating) }}</span>
                                    </div>
                                @else
                                    <button type="button"
                                        onclick="openReviewModal({{ $item->id }}, '{{ addslashes($item->assignedProvider?->name ?? 'المتطوع') }}', '{{ route('service-requests.reviews.store', $item) }}')"
                                        class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                        ⭐ تقييم الخدمة
                                    </button>
                                @endif
                            </div>

                        {{-- 8. حالات بحاجة إلى إجراء: اعتذر مقدم الخدمة --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_APOLOGIZED)
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                <span>⚠️</span>
                                <span>اعتذر مقدم الخدمة — تم فتح الطلب للبحث عن متطوع بديل أو يمكنك إعادة الجدولة.</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button"
                                    onclick="openRescheduleModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.reschedule', $item) }}')"
                                    class="rounded-2xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer">
                                    🔄 موعد جديد وإعادة النشر
                                </button>
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="rounded-2xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition cursor-pointer">
                                    🚫 إلغاء الطلب
                                </button>
                            </div>

                        {{-- 9. حالات بحاجة إلى إجراء: مقدم الخدمة متأخر (صفحة 11 و 18) --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                                <span>⏳</span>
                                <span>مقدم الخدمة متأخر — يمكنك الانتظار مهلة 30 دقيقة أو طلب بديل فوراً.</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('service-requests.search-alternative', $item) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit"
                                        class="rounded-2xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer">
                                        🔍 البحث عن بديل
                                    </button>
                                </form>
                                @if ($item->assignedProvider)
                                    <button type="button"
                                        onclick="openContactModal('{{ addslashes($item->assignedProvider->name) }}', '{{ addslashes($item->assignedProvider->registrationProfile?->phone ?? '0599000000') }}')"
                                        class="rounded-2xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                        📞 تواصل معه
                                    </button>
                                @endif
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="rounded-2xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition cursor-pointer">
                                    🚫 إلغاء
                                </button>
                            </div>

                        {{-- 10. حالة الطلب الملغى --}}
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_CANCELLED)
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
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center">
                    <span class="text-4xl">📋</span>
                    <h3 class="mt-4 text-lg font-black text-slate-800">لا توجد طلبات في هذا القسم</h3>
                    <p class="mt-1 text-xs text-slate-500">يمكنك إنشاء طلب مساعدة جديد في أي وقت وسيتولى المتطوعون تقديم يد العون.</p>
                    <button type="button" onclick="openCreateRequestModal()"
                        class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-lg hover:bg-[#52643a] transition cursor-pointer">
                        <span>+ إنشاء طلب مساعدة جديد</span>
                    </button>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
