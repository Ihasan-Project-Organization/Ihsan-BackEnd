<x-provider-layout>
    <div class="space-y-8">

        {{-- رأس الصفحة --}}
        <div>
            <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">الأداء والتقييم</h1>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">مستوى التميز والتقييمات الموثقة من كبار السن وفق نظام المستويات المعتمد.</p>
        </div>

        {{-- بطاقة المستوى Tier الرئيسية المعتمدة --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-l from-[#243516] via-[#31421e] to-[#4a5f31] p-6 text-white shadow-xl sm:p-8">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1 text-xs font-bold text-[#dfe6d5] border border-white/15 backdrop-blur-sm">
                        <span>🏆 نظام المستويات المعتمد (Tier System)</span>
                    </div>

                    <h2 class="mt-3 text-2xl sm:text-3xl font-black text-white">
                        مستواك الحالي: المستوى {{ $tier }} (Tier {{ $tier }})
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-[#dfe6d5] max-w-xl leading-relaxed">
                        @if ($tier === 1)
                            المستوى الأساسي المعتمد لمقدمي الخدمة الجدد. يتيح لك تصفح وتلقي كافة طلبات المساعدة المتاحة.
                        @elseif ($tier === 2)
                            مستوى متقدم يعكس التزامك وجودة خدماتك العالية وخبرتك الموثقة مع كبار السن.
                        @else
                            أعلى مستويات التميز والموثوقية في منصة إحسان مع كامل الأولوية والتقدير المؤسسي.
                        @endif
                    </p>
                </div>

                {{-- صندوق شروط الترقية والتقدم --}}
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-md border border-white/15 max-w-sm w-full">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span>التقدم نحو المستوى التالي</span>
                        <span class="text-amber-300 font-black">{{ $tierProgress }}%</span>
                    </div>

                    <div class="mt-2.5 h-2.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-amber-300 transition-all duration-500 rounded-full"
                            style="width: {{ $tierProgress }}%"></div>
                    </div>

                    <div class="mt-3 text-[11px] text-[#dfe6d5] space-y-1">
                        @if ($tier === 1)
                            <div class="flex justify-between">
                                <span>المهام المنجزة:</span>
                                <span class="font-bold text-white">{{ $totalServices }} / 10 مهام</span>
                            </div>
                            <div class="flex justify-between">
                                <span>التقييم المطلوب:</span>
                                <span class="font-bold text-white">4.0+ (حالياً: {{ number_format($avgRating, 1) }})</span>
                            </div>
                            <p class="mt-1 text-amber-200 font-semibold">متبقي {{ $tasksToNextTier }} مهام للترقية إلى Tier 2.</p>
                        @elseif ($tier === 2)
                            <div class="flex justify-between">
                                <span>المهام المنجزة:</span>
                                <span class="font-bold text-white">{{ $totalServices }} / 30 مهمة</span>
                            </div>
                            <div class="flex justify-between">
                                <span>التقييم المطلوب:</span>
                                <span class="font-bold text-white">4.3+ (حالياً: {{ number_format($avgRating, 1) }})</span>
                            </div>
                            <p class="mt-1 text-amber-200 font-semibold">متبقي {{ $tasksToNextTier }} مهام للترقية إلى Tier 3.</p>
                        @else
                            <p class="text-emerald-200 font-bold text-xs">✓ وصلت إلى أعلى مستوى تميز معتمد في المنصة.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-16 -left-16 h-60 w-60 rounded-full bg-[#718256]/30 blur-2xl"></div>
        </div>

        {{-- إحصائيات الأداء الثلاث --}}
        <div class="grid gap-6 sm:grid-cols-3">
            {{-- متوسط تقييم النجوم --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">متوسط التقييم العام</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-4xl sm:text-5xl font-black text-[#31421e]">{{ number_format($avgRating, 1) }}</span>
                        <div>
                            <div class="flex items-center text-amber-400 text-lg">
                                ★★★★★
                            </div>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                بناءً على {{ $reviews->total() }} تقييماً
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 rounded-2xl bg-[#f8faf6] p-3 border border-[#dfe6d5] text-[11px] text-slate-600">
                    ⭐ تقييم إجباري بسيط (1 إلى 5 نجوم) يضعه كبار السن عند تأكيد إتمام الخدمة.
                </div>
            </div>

            {{-- المهام المنفذة --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">المهام المكتملة</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-4xl sm:text-5xl font-black text-emerald-800">{{ $totalServices }}</span>
                        <div>
                            <span class="text-xs font-bold text-emerald-600">✓ خدمة منجزة</span>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                تسليم مؤكد مع كبار السن
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 rounded-2xl bg-[#f8faf6] p-3 border border-[#dfe6d5] text-[11px] text-slate-600">
                    📜 مؤهلة لطلب شهادات تطوع رقمية موثقة من المنصة.
                </div>
            </div>

            {{-- التقييمات الإيجابية الكاملة --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400">التقييمات الممتازة</span>
                    <div class="mt-4 flex items-center gap-4">
                        <span class="text-4xl sm:text-5xl font-black text-amber-500">{{ $fiveStarsCount }}</span>
                        <div>
                            <span class="text-xs font-bold text-amber-600">★★★★★</span>
                            <p class="mt-1 text-xs font-bold text-slate-500">
                                تقييمات بدرجة 5 من 5
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 rounded-2xl bg-[#f8faf6] p-3 border border-[#dfe6d5] text-[11px] text-slate-600">
                    🌟 ثناء وتقدير مباشر من المستفيدين يعزز ترقية مستواك.
                </div>
            </div>
        </div>

        {{-- سجل آراء المستفيدين --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-[#31421e]">آراء كبار السن</h2>
                    <p class="text-xs text-slate-500">سجل الانطباعات والتعليقات المكتوبة بعد إتمام المساعدات</p>
                </div>
            </div>

            @if ($reviews->count() > 0)
                <div class="space-y-3">
                    @foreach ($reviews as $rev)
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800">{{ $rev->elderly?->name ?? 'كبير السن' }}</h3>
                                    <span class="text-xs text-slate-400">• {{ $rev->created_at->translatedFormat('d F Y') }}</span>
                                    @if ($rev->serviceRequest)
                                        <span class="text-xs text-slate-400 font-mono">({{ $rev->serviceRequest->public_id }})</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-xs leading-6 text-slate-600 font-medium">
                                    "{{ $rev->comment ?? 'خدمة ممتازة، بارك الله فيكم وفي جهودكم الطيبة.' }}"
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-amber-400 font-black text-sm shrink-0 bg-amber-50 px-3 py-1.5 rounded-2xl border border-amber-200">
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
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm space-y-2">
                    <span class="text-4xl">⭐</span>
                    <h3 class="text-sm font-bold text-slate-700">لا توجد تقييمات مكتوبة حتى الآن</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">ستظهر هنا التقييمات والرسائل فور تأكيد كبار السن للمهام المنفذة.</p>
                </div>
            @endif
        </div>

    </div>
</x-provider-layout>
