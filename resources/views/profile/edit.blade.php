<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-bold text-[#718256]">لوحة التحكم</p>
            <h1 class="mt-1 text-2xl font-black text-[#31421e]">الإعدادات</h1>
            <p class="text-xs text-slate-500 mt-1">إدارة معلومات حسابك وتفضيلاتك في منصة إحسان</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 sm:py-10">
        {{-- بانر الملف الشخصي المستوحى من settings.html --}}
        <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-[#31421e] to-[#485f2f] p-6 text-white shadow-md sm:p-8">
            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5 sm:gap-6 text-center sm:text-right">
                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                        class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl object-cover border-4 border-white/20 shadow-lg">
                @else
                    <div class="flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-2xl bg-white/10 text-3xl font-black text-white border-2 border-white/20 shadow-md">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h2 class="text-2xl font-black truncate">{{ $user->name }}</h2>
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-extrabold text-[#dfe6d5]">
                            @if ($user->isAdmin())
                                مدير النظام
                            @elseif ($user->isProvider())
                                مقدم خدمة متطوع (Tier {{ $user->serviceProviderProfile?->tier ?? 1 }})
                            @else
                                كبير السن / مستفيد
                            @endif
                        </span>
                    </div>
                    <p class="mt-1.5 text-sm text-[#dfe6d5] font-mono" dir="ltr">{{ $user->email }}</p>
                    @if ($user->phone_number)
                        <p class="mt-1 text-xs text-[#dfe6d5]/80 font-mono" dir="ltr">📞 {{ $user->phone_number }}</p>
                    @endif
                </div>
            </div>
            <div class="absolute -bottom-12 -left-12 h-44 w-44 rounded-full bg-[#718256]/30"></div>
        </div>

        {{-- قائمة كروت الإعدادات المعتمدة --}}
        <div class="space-y-6">
            {{-- 1. بطاقة تعديل المعلومات الشخصية --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- 2. بطاقة تغيير كلمة المرور --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- 3. كروت تفضيلات الواجهة البصرية (مستوحاة من settings.html - عناصر بصرية بدون وظيفة حالياً) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <header class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#faebd7] text-lg text-[#d2691e]">
                            🎨
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-[#31421e]">تفضيلات الواجهة والقراءة</h2>
                            <p class="text-xs text-slate-500">خيارات بصرية مساعدة لتسهيل القراءة وتجربة الاستخدام.</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-[10px] font-bold text-emerald-700">مفعل للحساب</span>
                </header>

                <div class="space-y-5">
                    {{-- عنصر حجم الخط التفاعلي - 5 أزرار للفئات --}}
                    <div x-data="{
                        scale: (function() {
                            try {
                                return parseInt(localStorage.getItem('ihsan_font_scale')) || 100;
                            } catch (e) {
                                return 100;
                            }
                        })(),
                        apply(val) {
                            this.scale = parseInt(val);
                            document.documentElement.style.fontSize = this.scale + '%';
                            try {
                                localStorage.setItem('ihsan_font_scale', this.scale);
                            } catch (e) {}
                        }
                    }" class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#faebd7] text-sm font-black text-[#d2691e] shadow-xs">TT</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">حجم الخط</h3>
                                    <p class="text-xs text-slate-500">اختر الحجم المناسب لتسهيل القراءة وتكبير كافة نصوص صفحات المنصة</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500">الحجم المفعّل:</span>
                                <span class="text-xs font-black text-[#31421e] bg-white px-3 py-1 rounded-lg border border-[#dfe6d5] shadow-xs" 
                                    x-text="scale === 90 ? 'صغير (90%)' : (scale === 100 ? 'عادي (100%)' : (scale === 110 ? 'متوسط (110%)' : (scale === 120 ? 'كبير (120%)' : 'كبير جداً (130%)')))">
                                </span>
                            </div>
                        </div>

                        {{-- أزرار الفئات الـ 5 --}}
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                            {{-- 1. فئة صغير --}}
                            <button type="button" @click="apply(90)"
                                :class="scale === 90 
                                    ? 'bg-[#31421e] text-white border-[#31421e] shadow-md ring-2 ring-[#31421e]/30 scale-[1.02]' 
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-[#718256] hover:bg-[#f4f7f0]'"
                                class="flex flex-col items-center justify-center py-3 px-3 rounded-xl border text-center transition-all cursor-pointer">
                                <span class="text-xs font-bold">صغير</span>
                                <span class="text-[10px] font-medium opacity-80 mt-0.5">90%</span>
                            </button>

                            {{-- 2. فئة عادي (الافتراضي) --}}
                            <button type="button" @click="apply(100)"
                                :class="scale === 100 
                                    ? 'bg-[#31421e] text-white border-[#31421e] shadow-md ring-2 ring-[#31421e]/30 scale-[1.02]' 
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-[#718256] hover:bg-[#f4f7f0]'"
                                class="flex flex-col items-center justify-center py-3 px-3 rounded-xl border text-center transition-all cursor-pointer">
                                <span class="text-xs font-bold">عادي</span>
                                <span class="text-[10px] font-medium opacity-80 mt-0.5">الافتراضي (100%)</span>
                            </button>

                            {{-- 3. فئة متوسط --}}
                            <button type="button" @click="apply(110)"
                                :class="scale === 110 
                                    ? 'bg-[#31421e] text-white border-[#31421e] shadow-md ring-2 ring-[#31421e]/30 scale-[1.02]' 
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-[#718256] hover:bg-[#f4f7f0]'"
                                class="flex flex-col items-center justify-center py-3 px-3 rounded-xl border text-center transition-all cursor-pointer">
                                <span class="text-xs font-bold">متوسط</span>
                                <span class="text-[10px] font-medium opacity-80 mt-0.5">110%</span>
                            </button>

                            {{-- 4. فئة كبير --}}
                            <button type="button" @click="apply(120)"
                                :class="scale === 120 
                                    ? 'bg-[#31421e] text-white border-[#31421e] shadow-md ring-2 ring-[#31421e]/30 scale-[1.02]' 
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-[#718256] hover:bg-[#f4f7f0]'"
                                class="flex flex-col items-center justify-center py-3 px-3 rounded-xl border text-center transition-all cursor-pointer">
                                <span class="text-xs font-bold">كبير</span>
                                <span class="text-[10px] font-medium opacity-80 mt-0.5">120%</span>
                            </button>

                            {{-- 5. فئة كبير جداً --}}
                            <button type="button" @click="apply(130)"
                                :class="scale === 130 
                                    ? 'bg-[#31421e] text-white border-[#31421e] shadow-md ring-2 ring-[#31421e]/30 scale-[1.02]' 
                                    : 'bg-white text-slate-700 border-slate-200 hover:border-[#718256] hover:bg-[#f4f7f0]'"
                                class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center py-3 px-3 rounded-xl border text-center transition-all cursor-pointer">
                                <span class="text-xs font-bold">كبير جداً</span>
                                <span class="text-[10px] font-medium opacity-80 mt-0.5">130%</span>
                            </button>
                        </div>
                    </div>

                    {{-- عنصر إشعارات الصوت (من settings.html) --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#e6edd9] text-sm text-[#354e20]">🔊</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">إشعارات الصوت</h3>
                                <p class="text-xs text-slate-500">تشغيل أو إيقاف صوت التنبيهات عند وصول إشعار جديد</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31421e]"></div>
                        </label>
                    </div>

                    {{-- عنصر المساعدة والدعم (من settings.html) --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#f0f0f0] text-sm text-slate-500">❓</span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">المساعدة والدعم الفني</h3>
                                <p class="text-xs text-slate-500">تواصل مع فريق منصة إحسان لأي استفسار أو إرشاد</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-[#31421e] bg-white border border-[#dfe6d5] px-3 py-1.5 rounded-lg shadow-sm">
                            الدعم متاح 24/7
                        </span>
                    </div>
                </div>
            </div>

            {{-- 4. بطاقة منطقة الخطر (حذف الحساب) --}}
            <div class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>

            {{-- 5. زر تسجيل الخروج كما في settings.html --}}
            <div class="pt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-2xl border-2 border-red-200 bg-red-50/50 py-3.5 px-4 text-sm font-bold text-red-700 hover:bg-red-100 transition cursor-pointer shadow-sm">
                        <span>🚪 تسجيل الخروج من الحساب</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
