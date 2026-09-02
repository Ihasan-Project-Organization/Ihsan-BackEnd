<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- رأس الصفحة --}}
        <div>
            <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">الأداء والتقييم</h1>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">صورة واضحة عن جودة خدماتك ومدى التزامك في منصة إحسان.</p>
        </div>

        {{-- لوحة الأداء الثنائية (مستوحاة بدقة من صفحة 20 بالملف) --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- الجانب الأيمن: تقييم المستفيدين بالنجوم --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">تقييم المستفيدين</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-5xl font-black text-[#31421e]">{{ number_format($avgRating, 1) }}</span>
                        <div>
                            <div class="flex items-center text-amber-400 text-xl">
                                ★★★★★
                            </div>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                بناءً على {{ $reviews->total() }} تقييماً من أصل {{ $totalServices }} خدمة مكتملة
                            </p>
                        </div>
                    </div>

                    {{-- تفاصيل معايير تقييم المستفيد --}}
                    <div class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-xs">
                        <div class="flex items-center justify-between text-slate-700">
                            <span>حسن التعامل واللباقة</span>
                            <span class="font-bold text-[#31421e]">5.0 ★</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-700">
                            <span>الالتزام بالموعد المحدد</span>
                            <span class="font-bold text-[#31421e]">4.9 ★</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-700">
                            <span>جودة وأمانة تنفيذ الخدمة</span>
                            <span class="font-bold text-[#31421e]">4.8 ★</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-700">
                            <span>الشعور بالأمان والراحة</span>
                            <span class="font-bold text-[#31421e]">5.0 ★</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5] text-[11px] text-slate-600">
                    <span class="font-bold text-[#31421e]">📌 تقييم المستفيد:</span> نجوم من 1 إلى 5 مع تعليقات اختيارية يضعها كبير السن بعد إتمام الخدمة.
                </div>
            </div>

            {{-- الجانب الأيسر: مؤشر الالتزام (النظام) --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400">مؤشر النظام الآلي</span>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-800">
                            {{ $setting->commitment_score }}% مؤشر الالتزام
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        {{-- الحضور في الموعد --}}
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-700">الحضور في الموعد المحدد</span>
                                <span class="text-[#31421e]">{{ $setting->punctuality_rate }}%</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $setting->punctuality_rate }}%"></div>
                            </div>
                        </div>

                        {{-- إكمال الطلبات المقبولة --}}
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-700">إكمال الطلبات المقبولة</span>
                                <span class="text-[#31421e]">{{ $setting->completion_rate }}%</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#52643a] rounded-full" style="width: {{ $setting->completion_rate }}%"></div>
                            </div>
                        </div>

                        {{-- معدل سرعة الاستجابة --}}
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-700">معدل سرعة الاستجابة</span>
                                <span class="text-[#31421e]">{{ $setting->response_rate }}%</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $setting->response_rate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-[#eef2e8] p-3.5 border border-[#dfe6d5] text-[11px] text-[#31421e]">
                    <span class="font-bold">⚙️ مؤشر النظام:</span> يحسب آلياً بناءً على الحضور، التأخير، والاعتذارات، وهو منفصل تماماً عن تقييم النجوم.
                </div>
            </div>

        </div>

        {{-- 4 بطاقات تفصيلية للأرقام (صفحة 20) --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-400">خدمة مكتملة</span>
                <p class="mt-2 text-3xl font-black text-emerald-800">{{ $totalServices }}</p>
                <span class="text-[11px] text-emerald-600 font-semibold">✓ تم إنجازها بنجاح</span>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-400">في الموعد</span>
                <p class="mt-2 text-3xl font-black text-[#31421e]">{{ $onTimeCount }}</p>
                <span class="text-[11px] text-[#52643a] font-semibold">🕒 وصول دقيق</span>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-400">اعتذاران مسجلان</span>
                <p class="mt-2 text-3xl font-black text-amber-700">{{ $apologiesCount }}</p>
                <span class="text-[11px] text-amber-600 font-semibold">⚠️ اعتذار مسبق</span>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-400">تقييم 5 نجوم</span>
                <p class="mt-2 text-3xl font-black text-amber-500">{{ $fiveStarsCount }}</p>
                <span class="text-[11px] text-amber-600 font-semibold">★ أعلى تقييم</span>
            </div>
        </div>

        {{-- سجل آراء المستفيدين --}}
        <div class="space-y-4">
            <h2 class="text-xl font-black text-[#31421e]">آخر آراء المستفيدين</h2>

            @if ($reviews->count() > 0)
                <div class="space-y-3">
                    @foreach ($reviews as $rev)
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800">{{ $rev->elderly->name }}</h3>
                                    <span class="text-xs text-slate-400">• {{ $rev->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                                <p class="mt-2 text-xs leading-6 text-slate-600 font-medium">
                                    "{{ $rev->comment ?? 'خدمة ممتازة، بارك الله فيكم وفي جهودكم الطيبة.' }}"
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-amber-400 font-black text-sm shrink-0">
                                @for ($i = 0; $i < $rev->rating; $i++) ★ @endfor
                                <span class="text-xs font-bold text-slate-700 mr-1">({{ $rev->rating }}/5)</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <span class="text-3xl">⭐</span>
                    <p class="mt-2 text-xs text-slate-500 font-bold">لا توجد تقييمات مكتوبة حتى الآن. ستظهر هنا فور تقييم كبار السن لخدماتك.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

