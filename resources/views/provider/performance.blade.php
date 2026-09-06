<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- رأس الصفحة --}}
        <div>
            <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">الأداء والتقييم</h1>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">صورة واضحة عن جودة خدماتك وتقييمات كبار السن في منصة إحسان.</p>
        </div>

        {{-- لوحة الأداء المبسطة: متوسط النجوم + عدد المهام المكتملة --}}
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">متوسط تقييم النجوم</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-5xl font-black text-[#31421e]">{{ number_format($avgRating, 1) }}</span>
                        <div>
                            <div class="flex items-center text-amber-400 text-xl">
                                ★★★★★
                            </div>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                بناءً على {{ $reviews->total() }} تقييماً
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5] text-[11px] text-slate-600">
                    <span class="font-bold text-[#31421e]">📌 تقييم المستفيدين:</span> نجوم من 1 إلى 5 مع تعليقات اختيارية يضعها كبار السن بعد إتمام الخدمة.
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">المهام المنفذة</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-5xl font-black text-emerald-800">{{ $totalServices }}</span>
                        <div>
                            <span class="text-sm font-bold text-emerald-600">✓ خدمة مكتملة</span>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                تم إنجازها بنجاح وتسليمها للمستفيدين
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5] text-[11px] text-slate-600">
                    {{-- TODO: reconnect per §6 Tier-only system in stage 3.2/3.3 --}}
                    <span class="font-bold text-[#31421e]">🌟 مجتمع العطاء:</span> كل خدمة تقدمها تصنع فارقاً حقيقياً في حياة كبار السن.
                </div>
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
                                @for ($i = 0; $i < $rev->stars; $i++) ★ @endfor
                                <span class="text-xs font-bold text-slate-700 mr-1">({{ $rev->stars }}/5)</span>
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
