<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-6">

        {{-- رأس الصفحة --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">الطلبات المتاحة</h1>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">طلبات مساعدة جديدة مطابقة لخدماتك ومنطقتك وأوقات توفرك.</p>
            </div>

            <a href="{{ route('provider.availability') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                <span>⚙️ إعدادات التوفر والمنطقة</span>
            </a>
        </div>

        {{-- تنبيهات --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold">
                {{ match(session('status')) {
                    'task-dismissed' => 'تم تجاوز الطلب وإخفاؤه من قائمتك دون التأثير على التقييم أو مؤشر الالتزام.',
                    default => 'تم تنفيذ الإجراء بنجاح.'
                } }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- شريط الفلاتر والبحث (مطابق لصفحة 6 بالملف) --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            <form method="GET" action="{{ route('provider.available') }}" class="grid gap-3 md:grid-cols-[1.5fr_1fr_1fr_auto]">
                {{-- حقل البحث --}}
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="ابحث بنوع الخدمة أو الحي أو العنوان..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                </div>

                {{-- فلتر نوع الخدمة --}}
                <div>
                    <select name="service_type" onchange="this.form.submit()" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="all" {{ $serviceType === 'all' || !$serviceType ? 'selected' : '' }}>جميع الخدمات ({{ $categoryCounts['all'] }})</option>
                        <option value="grocery" {{ $serviceType === 'grocery' ? 'selected' : '' }}>🛒 شراء أغراض منزلية ({{ $categoryCounts['grocery'] }})</option>
                        <option value="medical_escort" {{ $serviceType === 'medical_escort' ? 'selected' : '' }}>🚶‍♂️ مرافقة إلى موعد طبي ({{ $categoryCounts['medical_escort'] }})</option>
                        <option value="medicine" {{ $serviceType === 'medicine' ? 'selected' : '' }}>💊 إحضار دواء ({{ $categoryCounts['medicine'] }})</option>
                        <option value="home_help" {{ $serviceType === 'home_help' ? 'selected' : '' }}>🧹 مساعدة منزلية خفيفة ({{ $categoryCounts['home_help'] }})</option>
                    </select>
                </div>

                {{-- الترتيب --}}
                <div>
                    <select name="sort" onchange="this.form.submit()" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="nearest" {{ $sort === 'nearest' ? 'selected' : '' }}>📍 الأقرب مسافة أولاً</option>
                        <option value="soonest" {{ $sort === 'soonest' ? 'selected' : '' }}>🕒 الأقرب بالموعد</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                        بحث
                    </button>
                    @if ($search || ($serviceType && $serviceType !== 'all') || $sort !== 'nearest')
                        <a href="{{ route('provider.available') }}" class="rounded-2xl border border-slate-200 px-3 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-50 transition">
                            إلغاء الفلاتر
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- شبكة بطاقات الطلبات المتاحة --}}
        @if ($requests->count() > 0)
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($requests as $req)
                    <div class="flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-[#718256] hover:shadow-md transition">
                        <div>
                            {{-- رأس البطاقة: المسافة التقديرية واسم الخدمة --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#eef2e8] text-xl">
                                        {{ $req->service_type_icon }}
                                    </span>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900">{{ $req->service_type_label }}</h3>
                                        <p class="text-xs font-bold text-[#718256]">📍 {{ $req->district ?? 'حي الرمال' }}</p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600">
                                    + {{ $req->distance_km ?? 2.0 }} كم
                                </span>
                            </div>

                            {{-- وصف مختصر --}}
                            <p class="mt-4 text-xs leading-6 text-slate-600 line-clamp-2">
                                {{ $req->description }}
                            </p>

                            {{-- الموعد والمدة --}}
                            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <span>🕒</span>
                                    <span>{{ $req->scheduled_at->translatedFormat('l، d F - h:i A') }}</span>
                                </span>
                                <span class="text-slate-300">•</span>
                                <span>مدة متوقعة: ساعة</span>
                            </div>

                            {{-- تنبيه الخصوصية (صفحة 7) --}}
                            <div class="mt-4 rounded-2xl bg-amber-50/80 p-3 border border-amber-200/60 flex items-center gap-2 text-[11px] text-amber-800">
                                <span>🔒</span>
                                <span>يظهر العنوان الدقيق وبيانات التواصل بعد قبول الطلب.</span>
                            </div>
                        </div>

                        {{-- أزرار اتخاذ القرار (صفحة 6) --}}
                        <div class="mt-6 flex items-center gap-2 border-t border-slate-100 pt-4">
                            <form method="POST" action="{{ route('provider.tasks.accept', $req) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-[#31421e] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                                    قبول الطلب
                                </button>
                            </form>

                            <button type="button"
                                onclick="openDetailsModal('{{ $req->public_id }}', '{{ $req->service_type_label }}', '{{ addslashes($req->description) }}', '{{ $req->district ?? $req->location }} (الموقع التقريبي)', '{{ $req->scheduled_at->translatedFormat('l، d F - h:i A') }}', '{{ $req->service_type_icon }}')"
                                class="rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                عرض التفاصيل
                            </button>

                            <form method="POST" action="{{ route('provider.tasks.dismiss', $req) }}">
                                @csrf
                                <button type="submit" class="rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition cursor-pointer" title="تجاوز الطلب (إخفاء دون تأثير على التقييم)">
                                    تجاوز
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <span class="text-4xl">🔍</span>
                <h3 class="mt-3 text-base font-black text-slate-800">لا توجد طلبات متاحة مطابقة حالياً</h3>
                <p class="mt-1 text-xs text-slate-500">جرب تغيير الفلاتر أو توسيع نطاق البحث من إعدادات التوفر.</p>
                <div class="mt-4 flex items-center justify-center gap-3">
                    <a href="{{ route('provider.available') }}" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white">إعادة تعيين الفلاتر</a>
                    <a href="{{ route('provider.availability') }}" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700">تعديل نطاق التوفر</a>
                </div>
            </div>
        @endif

    </div>

    @include('provider.partials.modals')
</x-app-layout>

