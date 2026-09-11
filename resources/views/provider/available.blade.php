<x-provider-layout>
    <div class="space-y-6">

        {{-- رأس الصفحة --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">الطلبات المتاحة</h1>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">فرص مساعدة تطوعية منشورة وبانتظار قبول مقدم الخدمة.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('provider.tasks') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                    <span>📋 طلباتي المسندة</span>
                </a>
            </div>
        </div>

        {{-- تنبيهات --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span>✓ {{ match(session('status')) {
                    'task-dismissed' => 'تم تجاوز الطلب وإخفاؤه من قائمتك دون أي تأثير على تقييمك أو حسابك.',
                    'task-accepted' => 'تم قبول الطلب بنجاح ونقله إلى قائمة طلباتي.',
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

        {{-- شريط الفلاتر والبحث --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            <form method="GET" action="{{ route('provider.available') }}" class="grid gap-3 md:grid-cols-[1.5fr_1fr_1fr_auto]">
                {{-- حقل البحث --}}
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="ابحث برقم الطلب، نوع الخدمة، أو الحي..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20 transition">
                </div>

                {{-- فلتر نوع الخدمة --}}
                <div>
                    <select name="service_type" onchange="this.form.submit()" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="all" {{ $serviceType === 'all' || !$serviceType ? 'selected' : '' }}>جميع الخدمات ({{ $categoryCounts['all'] ?? $counts['all'] ?? 0 }})</option>
                        <option value="grocery" {{ $serviceType === 'grocery' ? 'selected' : '' }}>🛒 شراء أغراض منزلية ({{ $categoryCounts['grocery'] ?? $counts['grocery'] ?? 0 }})</option>
                        <option value="medical_escort" {{ $serviceType === 'medical_escort' ? 'selected' : '' }}>🚶‍♂️ مرافقة إلى موعد طبي ({{ $categoryCounts['medical_escort'] ?? $counts['medical_escort'] ?? 0 }})</option>
                        <option value="medicine" {{ $serviceType === 'medicine' ? 'selected' : '' }}>💊 إحضار دواء ({{ $categoryCounts['medicine'] ?? $counts['medicine'] ?? 0 }})</option>
                        <option value="home_help" {{ $serviceType === 'home_help' ? 'selected' : '' }}>🧹 مساعدة منزلية خفيفة ({{ $categoryCounts['home_help'] ?? $counts['home_help'] ?? 0 }})</option>
                    </select>
                </div>

                {{-- الترتيب --}}
                <div>
                    <select name="sort" onchange="this.form.submit()" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="soonest" {{ $sort === 'soonest' ? 'selected' : '' }}>🕒 الأقرب موعداً أولاً</option>
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>⚡ الأحدث نشراً</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                        تطبيق
                    </button>
                    @if ($search || ($serviceType && $serviceType !== 'all') || $sort !== 'soonest')
                        <a href="{{ route('provider.available') }}" class="rounded-2xl border border-slate-200 px-3 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-50 transition">
                            إلغاء
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
                            {{-- رأس البطاقة: نوع الخدمة والعنوان ورقم الطلب --}}
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl shadow-sm">
                                        {{ $req->service_type_icon }}
                                    </span>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900">{{ $req->service_type_label }}</h3>
                                        <p class="text-xs font-bold text-[#52643a] mt-0.5">📍 {{ $req->location }} (الموقع التقريبي)</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-400">{{ $req->public_id }}</span>
                            </div>

                            {{-- وصف مختصر --}}
                            <p class="mt-4 text-xs leading-6 text-slate-600 line-clamp-3">
                                {{ $req->description }}
                            </p>

                            {{-- الموعد والمدة المتوقعة --}}
                            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                <span class="flex items-center gap-1.5 font-bold text-slate-700 bg-slate-50 px-2.5 py-1 rounded-xl border border-slate-100">
                                    <span>🕒</span>
                                    <span>{{ $req->scheduled_at->translatedFormat('l، d F Y - h:i A') }}</span>
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="text-[11px] text-slate-400">المدة التقديرية: ساعة</span>
                            </div>

                            {{-- تنبيه الخصوصية المعتمد --}}
                            <div class="mt-4 rounded-2xl bg-amber-50/80 p-3 border border-amber-200/60 flex items-center gap-2 text-[11px] text-amber-800">
                                <span class="text-sm">🔒</span>
                                <span>يظهر العنوان الدقيق وبيانات التواصل المباشر بعد قبول الطلب.</span>
                            </div>
                        </div>

                        {{-- أزرار اتخاذ القرار المعتمدة --}}
                        <div class="mt-6 flex items-center gap-2 border-t border-slate-100 pt-4">
                            <form method="POST" action="{{ route('provider.tasks.accept', $req) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl bg-[#31421e] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                                    قبول الطلب
                                </button>
                            </form>

                            <button type="button"
                                onclick="openDetailsModal('{{ $req->public_id }}', '{{ $req->service_type_label }}', '{{ addslashes($req->description) }}', '{{ $req->location }} (الموقع التقريبي)', '{{ $req->scheduled_at->translatedFormat('l، d F Y - h:i A') }}', '{{ $req->service_type_icon }}')"
                                class="rounded-2xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                التفاصيل
                            </button>

                            <form method="POST" action="{{ route('provider.tasks.dismiss', $req) }}">
                                @csrf
                                <button type="submit" class="rounded-2xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition cursor-pointer" title="تجاوز الطلب وإخفاؤه">
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
            <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm space-y-3">
                <span class="text-4xl">🔍</span>
                <h3 class="text-base font-black text-slate-800">لا توجد طلبات متاحة مطابقة حالياً</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                    يتم بث طلبات كبار السن فور نشرها لمقدمي الخدمة المؤهلين. يمكنك العودة لاحقاً أو إعادة تعيين الفلاتر.
                </p>
                <div class="pt-2 flex items-center justify-center gap-3">
                    <a href="{{ route('provider.available') }}" class="rounded-2xl bg-[#31421e] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                        إعادة تعيين الفلاتر
                    </a>
                    <a href="{{ route('provider.tasks') }}" class="rounded-2xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        طلباتي المسندة
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-provider-layout>
