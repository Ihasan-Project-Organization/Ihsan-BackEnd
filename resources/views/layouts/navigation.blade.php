<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-[#dfe6d5] bg-white/95 backdrop-blur">
    @php($user = Auth::user())
    @php($isVolunteer = $user?->account_type === 'volunteer')

    <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6 lg:gap-8">
            <a href="{{ $isVolunteer ? route('provider.dashboard') : route('dashboard') }}" class="text-2xl font-black text-[#31421e]">إحسان</a>
            
            <div class="hidden items-center gap-1.5 sm:flex">
                @if ($isVolunteer)
                    {{-- روابط مقدم الخدمة (المتطوع) --}}
                    <a href="{{ route('provider.dashboard') }}"
                        class="rounded-xl px-3 py-2 text-xs font-bold transition {{ request()->routeIs('provider.dashboard') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                        الرئيسية
                    </a>

                    @php($availableCount = \App\Models\ServiceRequest::availableForProvider($user)->count())
                    <a href="{{ route('provider.available') }}"
                        class="relative rounded-xl px-3 py-2 text-xs font-bold transition {{ request()->routeIs('provider.available') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                        الطلبات المتاحة
                        @if ($availableCount > 0)
                            <span class="mr-1 inline-flex items-center justify-center rounded-full bg-emerald-600 px-1.5 py-0.5 text-[10px] font-black text-white">
                                {{ $availableCount }}
                            </span>
                        @endif
                    </a>

                    @php($activeTasksCount = \App\Models\ServiceRequest::where('assigned_provider_id', $user->id)->whereIn('status', [\App\Models\ServiceRequest::STATUS_ACCEPTED, \App\Models\ServiceRequest::STATUS_ON_THE_WAY, \App\Models\ServiceRequest::STATUS_ARRIVED, \App\Models\ServiceRequest::STATUS_IN_PROGRESS, \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION, \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED])->count())
                    <a href="{{ route('provider.tasks') }}"
                        class="relative rounded-xl px-3 py-2 text-xs font-bold transition {{ request()->routeIs('provider.tasks*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                        طلباتي
                        @if ($activeTasksCount > 0)
                            <span class="mr-1 inline-flex items-center justify-center rounded-full bg-blue-600 px-1.5 py-0.5 text-[10px] font-black text-white">
                                {{ $activeTasksCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('provider.performance') }}"
                        class="rounded-xl px-3 py-2 text-xs font-bold transition {{ request()->routeIs('provider.performance') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                        الأداء والتقييم
                    </a>

                    <a href="{{ route('provider.availability') }}"
                        class="rounded-xl px-3 py-2 text-xs font-bold transition {{ request()->routeIs('provider.availability') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                        التوفر والإعدادات
                    </a>
                @else
                    {{-- روابط كبير السن (المستفيد) --}}
                    <a href="{{ route('dashboard') }}"
                        class="rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-500 hover:bg-slate-50' }}">
                        الرئيسية
                    </a>

                    <a href="{{ route('service-requests.index') }}"
                        class="relative rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->routeIs('service-requests.*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-500 hover:bg-slate-50' }}">
                        طلباتي
                        @php($needsActionCount = $user->serviceRequests()->needsAction()->count())
                        @if ($needsActionCount > 0)
                            <span class="absolute -top-1 -left-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white shadow">
                                {{ $needsActionCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="rounded-xl px-3 py-2 text-xs font-bold {{ request()->routeIs('profile.*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-500 hover:bg-slate-50' }}">
                    الملف الشخصي
                </a>
            </div>
        </div>

        <div class="hidden items-center gap-3 sm:flex">
            @if (!$isVolunteer)
                <button type="button" onclick="openCreateRequestModal()"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                    <span class="text-sm">+</span>
                    <span>طلب مساعدة</span>
                </button>
            @else
                <a href="{{ route('provider.available') }}"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                    <span>🔍 الطلبات المتاحة</span>
                </a>
            @endif

            @if ($user->profile_photo_url)
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-9 w-9 rounded-full object-cover border-2 border-[#dfe6d5] shadow-sm">
            @else
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#eef2e8] text-xs font-black text-[#31421e]">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
            @endif

            <div class="text-left">
                <p class="text-xs font-bold text-slate-800">{{ $user->name }}</p>
                <p class="text-[10px] text-slate-400">{{ $isVolunteer ? 'مقدم خدمة معتمد' : 'كبير سن' }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-700 transition cursor-pointer">
                    تسجيل الخروج
                </button>
            </form>
        </div>

        {{-- زر فتح قائمة الموبايل --}}
        <button type="button" @click="open = !open"
            class="rounded-xl border border-slate-200 p-2 text-[#31421e] hover:bg-slate-50 sm:hidden cursor-pointer focus:outline-none" aria-label="فتح القائمة">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- القائمة المنسدلة لشاشات الموبايل --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-slate-100 bg-white px-4 py-4 sm:hidden shadow-lg">
        
        <div class="mb-4 flex items-center gap-3 rounded-2xl bg-[#f8faf6] p-3 border border-[#dfe6d5]">
            @if ($user->profile_photo_url)
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover border border-[#dfe6d5]">
            @else
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#31421e] text-sm font-black text-white">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="font-bold text-sm text-slate-800 truncate">{{ $user->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
            </div>
            <span class="rounded-full bg-[#eef2e8] px-2.5 py-1 text-[11px] font-bold text-[#31421e]">
                {{ $isVolunteer ? 'مقدم خدمة' : 'كبير سن' }}
            </span>
        </div>

        <div class="grid gap-1.5">
            @if ($isVolunteer)
                <a href="{{ route('provider.dashboard') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('provider.dashboard') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>🏠 الرئيسية</span>
                </a>
                <a href="{{ route('provider.available') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('provider.available') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>👁️ الطلبات المتاحة</span>
                    <span class="rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-black text-white">
                        {{ \App\Models\ServiceRequest::availableForProvider($user)->count() }}
                    </span>
                </a>
                <a href="{{ route('provider.tasks') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('provider.tasks*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>📋 طلباتي</span>
                </a>
                <a href="{{ route('provider.performance') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('provider.performance') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>⭐ الأداء والتقييم</span>
                </a>
                <a href="{{ route('provider.availability') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('provider.availability') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>⚙️ التوفر والإعدادات</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>🏠 الرئيسية</span>
                </a>
                <a href="{{ route('service-requests.index') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-2.5 text-sm font-bold {{ request()->routeIs('service-requests.*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>📋 طلباتي</span>
                </a>
                <button type="button" @click="open = false" onclick="openCreateRequestModal()"
                    class="mt-1 flex items-center justify-center gap-2 rounded-xl bg-[#31421e] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                    <span>+ طلب مساعدة جديد</span>
                </button>
            @endif

            <a href="{{ route('profile.edit') }}"
                class="flex items-center rounded-xl px-4 py-2.5 text-xs font-bold {{ request()->routeIs('profile.*') ? 'bg-[#eef2e8] text-[#31421e]' : 'text-slate-600 hover:bg-slate-50' }}">
                <span>👤 الملف الشخصي</span>
            </a>

            <div class="mt-2 pt-2 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center rounded-xl px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition cursor-pointer">
                        <span>🚪 تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
