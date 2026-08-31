<x-app-layout>
    @php($user = auth()->user())
    @php($profile = $user->registrationProfile)
    @php($isVolunteer = $user->account_type === 'volunteer')

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        {{-- بطاقة الترحيب الرئيسية --}}
        <section class="relative overflow-hidden rounded-3xl bg-[#31421e] px-6 py-9 text-white shadow-xl sm:px-10 lg:px-12 lg:py-12">
            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-bold text-[#dfe6d5]">
                        {{ $isVolunteer ? 'حساب متطوع معتمد' : 'حساب كبير سن' }}
                    </span>
                    <h1 class="mt-4 text-3xl font-black sm:text-4xl">مرحبًا، {{ $user->name }}</h1>
                    <p class="mt-3 leading-8 text-[#dfe6d5]">
                        {{ $isVolunteer ? 'شكرًا لانضمامك إلى مجتمع العطاء في إحسان. ملفك جاهز لاستعراض طلبات كبار السن وتقديم يد العون لهم.' : 'أهلًا بك في منصة إحسان. يمكنك إدارة طلباتك ومتابعة المساعدات بسهولة.' }}
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        @if (!$isVolunteer)
                            <a href="{{ route('service-requests.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-bold text-[#31421e] shadow-lg hover:bg-[#eef2e8] transition">
                                <span>📋 متابعة طلباتي</span>
                            </a>
                            <button type="button" onclick="openCreateRequestModal()"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-3 font-bold text-white hover:bg-white/20 transition cursor-pointer">
                                <span>+ طلب مساعدة جديد</span>
                            </button>
                        @else
                            <a href="{{ route('volunteer.tasks.index', ['tab' => 'available']) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-bold text-[#31421e] shadow-lg hover:bg-[#eef2e8] transition">
                                <span>🤝 استعراض الفرص التطوعية</span>
                            </a>
                            <a href="{{ route('volunteer.tasks.index', ['tab' => 'my_tasks']) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-3 font-bold text-white hover:bg-white/20 transition">
                                <span>⚡ مهامي الحالية</span>
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-4 py-3 font-bold text-white hover:bg-white/10 transition text-xs">
                            <span>تعديل الملف</span>
                        </a>
                    </div>
                </div>

                <div class="hidden shrink-0 sm:block">
                    @if ($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                            class="h-28 w-28 rounded-3xl object-cover border-4 border-white/20 shadow-2xl">
                    @else
                        <div class="flex h-28 w-28 items-center justify-center rounded-3xl bg-white/10 text-4xl font-black text-white border-2 border-white/10 shadow-xl">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="absolute -bottom-20 -left-16 hidden h-72 w-72 rounded-full bg-[#718256]/40 lg:block"></div>
        </section>

        {{-- إحصائيات سريعة للطلبات والحساب --}}
        @if (!$isVolunteer)
            @php($activeCount = $user->serviceRequests()->active()->count())
            @php($needsActionCount = $user->serviceRequests()->needsAction()->count())
            @php($completedCount = $user->serviceRequests()->completed()->count())

            <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('service-requests.index', ['tab' => 'active']) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-[#718256] transition block">
                    <p class="text-xs font-bold text-slate-400">الطلبات النشطة</p>
                    <p class="mt-2 text-2xl font-black text-[#31421e]">{{ $activeCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-[#718256]">قيد المتابعة والتنفيذ</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'needs_action']) }}"
                    class="rounded-2xl border {{ $needsActionCount > 0 ? 'border-amber-300 bg-amber-50/50' : 'border-slate-200 bg-white' }} p-5 shadow-sm hover:border-amber-400 transition block">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold {{ $needsActionCount > 0 ? 'text-amber-800' : 'text-slate-400' }}">بحاجة إلى إجراء</p>
                        @if ($needsActionCount > 0)
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </div>
                    <p class="mt-2 text-2xl font-black {{ $needsActionCount > 0 ? 'text-amber-700' : 'text-slate-700' }}">{{ $needsActionCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-slate-500">تتطلب اختيار موعد أو إعادة نشر</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'completed']) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 transition block">
                    <p class="text-xs font-bold text-slate-400">الطلبات المكتملة</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">{{ $completedCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-emerald-600">خدمات تم تنفيذها بنجاح</span>
                </a>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold text-slate-400">تاريخ الانضمام</p>
                    <p class="mt-2 text-lg font-black text-slate-800">{{ $user->created_at->translatedFormat('d M Y') }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-emerald-600">حساب موثّق ونشط</span>
                </article>
            </div>
        @else
            @php($availableCount = \App\Models\ServiceRequest::where('status', \App\Models\ServiceRequest::STATUS_PENDING_ACCEPTANCE)->count())
            @php($myTasksCount = \App\Models\ServiceRequest::where('assigned_provider_id', $user->id)->whereIn('status', [\App\Models\ServiceRequest::STATUS_ACCEPTED, \App\Models\ServiceRequest::STATUS_IN_PROGRESS, \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION])->count())
            @php($completedTasksCount = \App\Models\ServiceRequest::where('assigned_provider_id', $user->id)->where('status', \App\Models\ServiceRequest::STATUS_COMPLETED)->count())
            @php($avgRating = $user->receivedReviews()->avg('rating'))

            <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('volunteer.tasks.index', ['tab' => 'available']) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-[#718256] transition block">
                    <p class="text-xs font-bold text-slate-400">الفرص المتاحة</p>
                    <p class="mt-2 text-2xl font-black text-[#31421e]">{{ $availableCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-[#718256]">طلبات بانتظار قبول متطوع</span>
                </a>

                <a href="{{ route('volunteer.tasks.index', ['tab' => 'my_tasks']) }}"
                    class="rounded-2xl border {{ $myTasksCount > 0 ? 'border-blue-300 bg-blue-50/50' : 'border-slate-200 bg-white' }} p-5 shadow-sm hover:border-blue-400 transition block">
                    <p class="text-xs font-bold {{ $myTasksCount > 0 ? 'text-blue-800' : 'text-slate-400' }}">مهامي الحالية</p>
                    <p class="mt-2 text-2xl font-black {{ $myTasksCount > 0 ? 'text-blue-700' : 'text-slate-700' }}">{{ $myTasksCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-slate-500">مهام قيد التحرك والتنفيذ</span>
                </a>

                <a href="{{ route('volunteer.tasks.index', ['tab' => 'completed']) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 transition block">
                    <p class="text-xs font-bold text-slate-400">المهام المنجزة</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">{{ $completedTasksCount }}</p>
                    <span class="mt-1 block text-[11px] font-semibold text-emerald-600">خدمات تم تقديمها بنجاح</span>
                </a>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold text-slate-400">تقييم المتطوع</p>
                    <div class="mt-2 flex items-center gap-1.5">
                        <span class="text-2xl font-black text-amber-500">{{ $avgRating ? number_format($avgRating, 1) : '5.0' }}</span>
                        <span class="text-amber-400 text-lg">★</span>
                    </div>
                    <span class="mt-1 block text-[11px] font-semibold text-slate-500">من تقييمات كبار السن</span>
                </article>
            </div>
        @endif

        {{-- تفاصيل الحساب والمعلومات --}}
        <div class="mt-7 grid gap-7 lg:grid-cols-[1.35fr_.65fr]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-[#718256]">نظرة عامة</p>
                        <h2 class="mt-1 text-2xl font-black text-[#31421e]">بيانات الحساب</h2>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="text-sm font-bold text-[#52643a] hover:underline">تعديل</a>
                </div>
                <dl class="mt-7 grid gap-5 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-bold text-slate-400">البريد الإلكتروني</dt>
                        <dd class="mt-1 break-all font-bold text-slate-800">{{ $user->email }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-bold text-slate-400">رقم الجوال</dt>
                        <dd class="mt-1 font-bold text-slate-800">{{ $profile?->phone ?? 'غير مضاف' }}</dd>
                    </div>
                    @if ($isVolunteer)
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold text-slate-400">رقم الهوية</dt>
                            <dd class="mt-1 font-bold text-slate-800">{{ $profile?->identity_number ?? 'غير مضاف' }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold text-slate-400">المدينة / النطاق</dt>
                            <dd class="mt-1 font-bold text-slate-800">{{ $profile?->city ?? 'غزة' }}</dd>
                        </div>
                    @else
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold text-slate-400">المدينة</dt>
                            <dd class="mt-1 font-bold text-slate-800">{{ $profile?->city ?? 'غير مضافة' }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold text-slate-400">نوع السكن</dt>
                            <dd class="mt-1 font-bold text-slate-800">
                                {{ ['apartment' => 'شقة', 'house' => 'منزل مستقل', 'family' => 'سكن مع العائلة'][$profile?->housing_type] ?? 'غير مضاف' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </section>

            <aside class="rounded-2xl bg-[#eef2e8] p-6 sm:p-8">
                <p class="text-sm font-bold text-[#718256]">الخدمات والدعم</p>
                <h2 class="mt-2 text-2xl font-black text-[#31421e]">
                    {{ $isVolunteer ? 'مجتمع العطاء' : 'طلب مساعدة جديد' }}
                </h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    {{ $isVolunteer ? 'يمكنك استعراض كافة فرص التطوع المتاحة فوراً وتقديم الدعم المباشر لكبار السن.' : 'هل تحتاج مساعدة في شراء أدوية أو مرافقة طبية أو زيارة ودية؟ فريق المتطوعين جاهز لخدمتك.' }}
                </p>

                <div class="mt-6">
                    @if (!$isVolunteer)
                        <button type="button" onclick="openCreateRequestModal()"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#31421e] py-3 text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                            <span>+ إنشاء طلب مساعدة الآن</span>
                        </button>
                    @else
                        <a href="{{ route('volunteer.tasks.index') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#31421e] py-3 text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition">
                            <span>🤝 استعراض الفرص التطوعية</span>
                        </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
