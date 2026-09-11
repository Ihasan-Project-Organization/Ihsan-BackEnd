<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-bold text-[#718256]">لوحة التحكم</p>
                <h1 class="mt-1 text-2xl font-black text-[#31421e] flex items-center gap-2">
                    <span>الإشعارات</span>
                    <span class="text-xl">🔔</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1">تابع تنبيهات ومستجدات طلباتك وحسابك أولاً بأول</p>
            </div>

            @if (($counts['unread'] ?? 0) > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-[#31421e] bg-white px-4 py-2 text-xs font-bold text-[#31421e] shadow-sm hover:bg-[#eef2e8] transition cursor-pointer">
                        <span>✓ تحديد الكل كمقروء</span>
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-10">
        {{-- شريط التبويبات المعتمد من الفرونت إند --}}
        <div class="mb-6 border-b border-slate-200">
            <nav class="-mb-px flex gap-4 sm:gap-6" aria-label="تبويبات الإشعارات">
                <a href="{{ route('notifications.index', ['tab' => 'all']) }}"
                    class="flex items-center gap-2 border-b-2 py-3 text-sm font-extrabold transition {{ ($tab ?? 'all') === 'all' ? 'border-[#31421e] text-[#31421e]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    <span>الكل</span>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">{{ $counts['all'] ?? 0 }}</span>
                </a>

                <a href="{{ route('notifications.index', ['tab' => 'unread']) }}"
                    class="flex items-center gap-2 border-b-2 py-3 text-sm font-extrabold transition {{ ($tab ?? 'all') === 'unread' ? 'border-[#31421e] text-[#31421e]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    <span>غير مقروءة</span>
                    @if (($counts['unread'] ?? 0) > 0)
                        <span class="rounded-full bg-amber-500 px-2 py-0.5 text-xs font-bold text-white animate-pulse">{{ $counts['unread'] }}</span>
                    @else
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">0</span>
                    @endif
                </a>

                <a href="{{ route('notifications.index', ['tab' => 'requests']) }}"
                    class="flex items-center gap-2 border-b-2 py-3 text-sm font-extrabold transition {{ ($tab ?? 'all') === 'requests' ? 'border-[#31421e] text-[#31421e]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
                    <span>الطلبات</span>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">{{ $counts['requests'] ?? 0 }}</span>
                </a>
            </nav>
        </div>

        {{-- تنبيهات الحالة --}}
        @if (session('status') === 'notification-read')
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 flex items-center justify-between">
                <span>تم تعليم الإشعار كمقروء بنجاح.</span>
            </div>
        @elseif (session('status') === 'all-notifications-read')
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 flex items-center justify-between">
                <span>تم تعليم جميع الإشعارات كمقروءة بنجاح.</span>
            </div>
        @endif

        {{-- قائمة كروت الإشعارات --}}
        <div class="space-y-4">
            @forelse ($notifications as $notification)
                @php
                    $type = $notification->type;
                    $isRed = str_contains($type, 'problem') || str_contains($type, 'alert') || str_contains($type, 'cancelled') || str_contains($type, 'no_provider');
                    $isOrange = str_contains($type, 'delay') || str_contains($type, 'apolog') || str_contains($type, 'warning');
                    $isGreen = str_contains($type, 'upgrade') || str_contains($type, 'accept') || str_contains($type, 'completed') || str_contains($type, 'status');

                    $borderColor = $isRed ? 'border-r-4 border-r-rose-500' : ($isOrange ? 'border-r-4 border-r-amber-500' : ($isGreen ? 'border-r-4 border-r-emerald-500' : 'border-r-4 border-r-slate-400'));
                    $iconBg = $isRed ? 'bg-rose-50 text-rose-600' : ($isOrange ? 'bg-amber-50 text-amber-600' : ($isGreen ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'));
                    
                    $title = match(true) {
                        str_contains($type, 'apolog') => 'اعتذار عن الخدمة',
                        str_contains($type, 'delay') => 'تنبيه تأخر في التنفيذ',
                        str_contains($type, 'problem') => 'إحالة بلاغ للمراجعة الإدارية',
                        str_contains($type, 'no_provider') => 'لم يتوفر مقدم خدمة',
                        str_contains($type, 'upgrade') => 'ترقية المستوى التقديري (Tier)',
                        str_contains($type, 'downgrade') => 'تحديث المستوى التقديري (Tier)',
                        str_contains($type, 'alert') => 'تنبيه إداري مهم',
                        default => 'إشعار من النظام',
                    };
                @endphp

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md {{ $borderColor }} {{ !$notification->is_read ? 'bg-slate-50/50 ring-1 ring-amber-400/20' : '' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $iconBg }} text-lg shadow-sm">
                                @if ($isRed)
                                    ⚠️
                                @elseif ($isOrange)
                                    ⏱️
                                @elseif ($isGreen)
                                    ✅
                                @else
                                    📌
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-base font-black text-slate-900">{{ $title }}</h2>
                                    @if (!$notification->is_read)
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-black text-amber-800">جديد</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $notification->message }}</p>
                            </div>
                        </div>
                        <time datetime="{{ $notification->created_at->toIso8601String() }}" class="shrink-0 text-xs font-bold text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </time>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">
                        <div class="flex items-center gap-2">
                            @if (!$notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-[#31421e] hover:text-[#52643a] transition cursor-pointer">
                                        <span>✓ تحديد كمقروء</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <span>✓ مقروء</span>
                                </span>
                            @endif
                        </div>

                        {{-- روابط سريعة للطلبات إن كان الإشعار مرتبطاً بطلب --}}
                        @if (str_contains($type, 'provider') || str_contains($type, 'request') || str_contains($type, 'problem'))
                            <div>
                                @if (Auth::user()->isProvider())
                                    <a href="{{ route('provider.tasks') }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-[#31421e] transition">
                                        <span>الانتقال لطلباتي ←</span>
                                    </a>
                                @else
                                    <a href="{{ route('service-requests.index') }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-[#31421e] transition">
                                        <span>الانتقال لطلباتي ←</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-400">
                        📭
                    </div>
                    <h3 class="text-base font-bold text-slate-700">لا توجد إشعارات في هذا التبويب</h3>
                    <p class="mt-1 text-xs text-slate-500">ستظهر هنا كافة التنبيهات المرتبطة بنشاطك على المنصة فور حدوثها.</p>
                </div>
            @endforelse
        </div>

        {{-- ترقيم الصفحات --}}
        @if ($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
