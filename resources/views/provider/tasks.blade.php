<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">

        {{-- رأس الصفحة --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">طلباتي</h1>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">إدارة الطلبات القادمة والجارية ومتابعة تأكيد المستفيد.</p>
            </div>

            <a href="{{ route('provider.available') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                <span>+ تصفح الطلبات المتاحة</span>
            </a>
        </div>

        {{-- تنبيهات --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between">
                <span>✓ {{ match(session('status')) {
                    'task-accepted' => 'تم قبول الطلب بنجاح وإسناده لك.',
                    'heading-started' => 'تم تسجيل بدء التوجه وإشعار المستفيد.',
                    'arrival-confirmed' => 'تم تسجيل وصولك بنجاح.',
                    'service-started' => 'تم بدء تقديم الخدمة.',
                    'service-finished' => 'تم إرسال ملخص التنفيذ للمستفيد وبانتظار تأكيده.',
                    'delay-reported' => 'تم تسجيل إشعار التأخير.',
                    'apology-completed' => 'تم تسجيل اعتذارك وفصل الإسناد بنجاح.',
                    default => 'تم تنفيذ الإجراء بنجاح.'
                } }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600">✕</button>
            </div>
        @endif

        {{-- شريط التبويبات الرئيسي (مطابق لصفحة 8 بالملف) --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
            <a href="{{ route('provider.tasks', ['tab' => 'all', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'all' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                الكل ({{ $counts['all'] }})
            </a>
            <a href="{{ route('provider.tasks', ['tab' => 'upcoming', 'search' => $search]) }}"
                class="rounded-2xl px-4 py-2 text-xs font-bold transition {{ $tab === 'upcoming' ? 'bg-[#31421e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                القادمة ({{ $counts['upcoming'] }})
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

                        {{-- رأس البطاقة: العنوان والحالة وتاريخ الموعد --}}
                        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl">
                                    {{ $req->service_type_icon }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-black text-slate-900">{{ $req->service_type_label }}</h3>
                                        <span class="text-xs font-bold text-slate-400">{{ $req->public_id }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        📍 <span class="font-bold text-slate-700">{{ $req->location }}</span> • 🕒 {{ $req->scheduled_at->translatedFormat('l، d F - h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="rounded-full px-3.5 py-1 text-xs font-bold border {{ $req->status_badge_classes }}">
                                    {{ $req->status_label }}
                                </span>
                            </div>
                        </div>

                        {{-- المخطط المرحلي المتسلسل (الستيبر - مطابق لصفحات 8، 10، 13) --}}
                        @php($step = $req->step_index)
                        <div class="hidden sm:block">
                            <div class="relative flex items-center justify-between">
                                <div class="absolute left-0 top-1/2 -z-0 h-1 w-full -translate-y-1/2 bg-slate-100 rounded-full"></div>
                                <div class="absolute left-0 top-1/2 -z-0 h-1 -translate-y-1/2 bg-[#52643a] transition-all duration-500 rounded-full"
                                    style="width: {{ $step >= 7 ? '100%' : (($step - 1) / 6 * 100) . '%' }}"></div>

                                @php($steps = [
                                    1 => 'طلب متاح',
                                    2 => 'تم القبول',
                                    3 => 'في الطريق',
                                    4 => 'وصلت',
                                    5 => 'قيد التنفيذ',
                                    6 => 'بانتظار التأكيد',
                                    7 => 'مكتمل'
                                ])

                                @foreach ($steps as $idx => $label)
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition-all {{ $step >= $idx ? 'bg-[#31421e] text-white shadow-sm ring-4 ring-white' : 'bg-slate-200 text-slate-500' }}">
                                            @if ($step > $idx) ✓ @else {{ $idx }} @endif
                                        </div>
                                        <span class="mt-2 text-[10px] font-bold {{ $step >= $idx ? 'text-[#31421e]' : 'text-slate-400' }}">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- تفاصيل المستفيد والوصف --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs text-slate-700 leading-6">
                                <p class="font-bold text-slate-500 mb-1">تفاصيل الاحتياج:</p>
                                <p>{{ $req->description }}</p>
                            </div>

                            <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5] space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">كبير السن:</span>
                                    <span class="font-black text-[#31421e]">{{ $req->user->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">العنوان التفصيلي:</span>
                                    <span class="font-bold text-slate-800">{{ $req->location }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-500">رقم الهاتف:</span>
                                    <span class="font-bold text-slate-800" dir="ltr">{{ $req->user->registrationProfile?->phone ?? '0599000000' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- إذا تم الإبلاغ عن تأخير (صفحة 11) --}}
                        @if ($req->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                            <div class="rounded-2xl bg-amber-50 p-4 border border-amber-200 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-amber-900">
                                    <span class="text-base">⏳</span>
                                    <div>
                                        <span class="font-bold">تم إشعار المستفيد بالتأخير:</span>
                                        <span>{{ $req->delay_reason }} (الوصول المتوقع: {{ $req->expected_arrival_at?->format('h:i A') ?? 'قريباً' }})</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- إذا كانت هناك ملاحظات إنهاء مقدمة --}}
                        @if ($req->completion_notes)
                            <div class="rounded-2xl bg-emerald-50 p-3.5 border border-emerald-200 text-xs text-emerald-900">
                                <span class="font-bold">ملخص التنفيذ المرسل:</span>
                                <span>{{ $req->completion_notes }}</span>
                            </div>
                        @endif

                        {{-- إذا وجد تقييم --}}
                        @if ($req->review)
                            <div class="rounded-2xl bg-amber-50/70 p-4 border border-amber-200/80 flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-amber-900">تقييم كبير السن:</span>
                                    <p class="mt-1 text-xs text-slate-700 font-semibold">{{ $req->review->comment ?? 'خدمة ممتازة، بارك الله فيك.' }}</p>
                                </div>
                                <div class="flex items-center gap-1 text-amber-500 font-bold">
                                    <span>{{ $req->review->rating }}</span>
                                    <span>★</span>
                                </div>
                            </div>
                        @endif

                        {{-- أزرار وسير العمليات التفاعلية (صفحات 8، 10، 13، 17) --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- 1. حالة تم القبول أو متأخر: زر ابدأ التوجه --}}
                                @if (in_array($req->status, [\App\Models\ServiceRequest::STATUS_ACCEPTED, \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED]))
                                    <form method="POST" action="{{ route('provider.tasks.start-heading', $req) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                                            🚗 ابدأ التوجه
                                        </button>
                                    </form>

                                    <button type="button" onclick="openReportDelayModal('{{ route('provider.tasks.report-delay', $req) }}')"
                                        class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                        ⏳ توقع تأخير
                                    </button>
                                @endif

                                {{-- 2. حالة في الطريق: زر تأكيد الوصول --}}
                                @if ($req->status === \App\Models\ServiceRequest::STATUS_ON_THE_WAY)
                                    <form method="POST" action="{{ route('provider.tasks.confirm-arrival', $req) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-teal-700 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-teal-800 transition cursor-pointer">
                                            📍 تأكيد الوصول للموقع
                                        </button>
                                    </form>

                                    <button type="button" onclick="openReportDelayModal('{{ route('provider.tasks.report-delay', $req) }}')"
                                        class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition cursor-pointer">
                                        ⏳ توقع تأخير
                                    </button>
                                @endif

                                {{-- 3. حالة وصل: زر بدء الخدمة --}}
                                @if ($req->status === \App\Models\ServiceRequest::STATUS_ARRIVED)
                                    <form method="POST" action="{{ route('provider.tasks.start-service', $req) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-purple-700 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-purple-800 transition cursor-pointer">
                                            ⚡ بدء الخدمة
                                        </button>
                                    </form>
                                @endif

                                {{-- 4. حالة قيد التنفيذ: زر إنهاء الخدمة --}}
                                @if ($req->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                                    <button type="button" onclick="openFinishServiceModal('{{ route('provider.tasks.finish-service', $req) }}')"
                                        class="rounded-2xl bg-emerald-700 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-800 transition cursor-pointer">
                                        ✓ إنهاء الخدمة
                                    </button>
                                @endif

                                {{-- تواصل مع المستفيد --}}
                                <a href="tel:{{ $req->user->registrationProfile?->phone ?? '0599000000' }}"
                                    class="rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                    📞 تواصل مع المستفيد
                                </a>
                            </div>

                            {{-- زر الاعتذار (يختفي فقط عند إنهاء الخدمة) --}}
                            @if ($req->canBeApologized())
                                <div>
                                    <button type="button" onclick="openApologizeModal('{{ route('provider.tasks.apologize', $req) }}')"
                                        class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                        الاعتذار عن الطلب
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <span class="text-4xl">📋</span>
                <h3 class="mt-3 text-base font-black text-slate-800">لا توجد طلبات في هذا التبويب</h3>
                <p class="mt-1 text-xs text-slate-500">يمكنك استعراض الفرص التطوعية المتاحة وقبول مهمة جديدة.</p>
                <div class="mt-4">
                    <a href="{{ route('provider.available') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-2.5 text-xs font-bold text-white">
                        <span>استعراض الطلبات المتاحة</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    @include('provider.partials.modals')
</x-app-layout>

