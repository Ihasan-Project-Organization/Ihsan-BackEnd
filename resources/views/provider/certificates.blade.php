<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- رأس الصفحة --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">شهادات التطوع الرقمية</h1>
                <p class="mt-1 text-xs text-slate-500 sm:text-sm">توثيق رسمي ومعتمد لساعاتك وجهودك التطوعية في رعاية ومساندة كبار السن.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('provider.tasks', ['tab' => 'completed']) }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                    <span>✓ سجل الخدمات المكتملة</span>
                </a>
            </div>
        </div>

        {{-- تنبيهات --}}
        @if (session('status') === 'certificate-issued')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span class="flex items-center gap-2">
                    <span class="text-base">🎉</span>
                    <span>تهانينا! تم إصدار وتوثيق شهادة التطوع الرقمية بنجاح. يمكنك معاينتها أو طباعتها أدناه.</span>
                </span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span>⚠️ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-rose-600 hover:text-rose-900 cursor-pointer">✕</button>
            </div>
        @endif

        {{-- بطاقة الملخص وطلب إصدار شهادة جديدة --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-l from-[#243516] via-[#31421e] to-[#455a2c] p-6 text-white shadow-xl sm:p-8">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-[#dfe6d5] border border-white/10">
                        <span>📜</span>
                        <span>اعتماد رسمي وموثق</span>
                    </span>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-black text-white">
                        سجل عطائك يستحق التوثيق
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-[#dfe6d5] max-w-xl leading-relaxed">
                        تُصدر منصة إحسان شهادات تطوع رقمية معتمدة برقم تسلسلي فريد يمكن التحقق منه، بناءً على المهام المنجزة الموثقة مع كبار السن.
                    </p>

                    {{-- أرقام سريعة --}}
                    <div class="mt-6 flex flex-wrap items-center gap-6">
                        <div>
                            <span class="text-[11px] font-bold text-[#dfe6d5]">المهام المنفذة</span>
                            <p class="text-2xl font-black text-white">{{ $completedCount }} مهمة</p>
                        </div>
                        <div class="h-8 w-px bg-white/20"></div>
                        <div>
                            <span class="text-[11px] font-bold text-[#dfe6d5]">الساعات المعتمدة</span>
                            <p class="text-2xl font-black text-white">{{ $estimatedHours }} ساعة</p>
                        </div>
                        <div class="h-8 w-px bg-white/20"></div>
                        <div>
                            <span class="text-[11px] font-bold text-[#dfe6d5]">الشهادات الصادرة</span>
                            <p class="text-2xl font-black text-amber-300">{{ $certificates->count() }} شهادة</p>
                        </div>
                    </div>
                </div>

                {{-- زر الإجراء --}}
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-md border border-white/15 max-w-sm w-full text-center">
                    <span class="text-3xl">🏅</span>
                    <h3 class="mt-2 text-sm font-black text-white">إصدار شهادة تطوع جديدة</h3>
                    <p class="mt-1 text-[11px] text-[#dfe6d5]">
                        @if ($completedCount > 0)
                            يمكنك طلب شهادة رقمية حالية توثق كامل سجلك التطوعي حتى اليوم.
                        @else
                            أكمل خدمة تطوعية واحدة على الأقل لتتمكن من إصدار أول شهادة تطوع.
                        @endif
                    </p>

                    <form method="POST" action="{{ route('provider.certificates.request') }}" class="mt-4">
                        @csrf
                        <button type="submit" {{ $completedCount < 1 ? 'disabled' : '' }}
                            class="w-full rounded-2xl {{ $completedCount >= 1 ? 'bg-amber-400 text-slate-900 hover:bg-amber-300 cursor-pointer shadow-lg' : 'bg-white/20 text-white/50 cursor-not-allowed' }} py-3 text-xs font-black transition">
                            طلب إصدار الشهادة الآن
                        </button>
                    </form>
                </div>
            </div>

            <div class="absolute -bottom-16 -left-16 h-60 w-60 rounded-full bg-[#718256]/30 blur-2xl"></div>
        </div>

        {{-- قائمة الشهادات الصادرة --}}
        <div class="space-y-6">
            <div>
                <h2 class="text-xl font-black text-[#31421e]">الشهادات المصدرة</h2>
                <p class="text-xs text-slate-500">سجل شهادات التطوع الرسمية الممنوحة لك من منصة إحسان</p>
            </div>

            @if ($certificates->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($certificates as $cert)
                        <div class="relative overflow-hidden rounded-3xl border-2 border-[#dfe6d5] bg-[#fbfaf6] p-6 sm:p-8 shadow-md flex flex-col justify-between space-y-6">
                            {{-- إطار زخرفي ناعم للشهادة --}}
                            <div class="absolute top-0 right-0 left-0 h-2 bg-gradient-to-r from-[#31421e] via-[#718256] to-[#df9b52]"></div>

                            <div>
                                <div class="flex items-center justify-between border-b border-[#dfe6d5] pb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#31421e] text-white font-black text-lg">إ</span>
                                        <div>
                                            <h4 class="text-xs font-black text-[#31421e]">منصة إحسان لرعاية كبار السن</h4>
                                            <p class="text-[10px] text-slate-400">وثيقة تطوعية رقمية معتمدة</p>
                                        </div>
                                    </div>
                                    <span class="rounded-xl bg-[#eef2e8] px-3 py-1 font-mono text-xs font-black text-[#31421e] border border-[#dfe6d5]">
                                        {{ $cert->certificate_number }}
                                    </span>
                                </div>

                                <div class="mt-6 text-center space-y-2">
                                    <span class="text-xs font-bold text-amber-700">شهادة شكر وتقدير</span>
                                    <h3 class="text-xl font-black text-slate-900">
                                        تُشهد منصة إحسان بأن المتطوع/ـة:
                                    </h3>
                                    <p class="text-2xl font-black text-[#31421e] py-1">
                                        {{ $provider->name }}
                                    </p>
                                    <p class="text-xs leading-6 text-slate-600 max-w-md mx-auto">
                                        قد ساهم/ـت بفاعلية وإخلاص في تقديم خدمات الرعاية والمساندة لكبار السن بإجمالي <strong class="text-[#31421e]">{{ $completedCount }} مهمة تطوعية</strong>، تقديراً لجهوده الطيبة ومسؤوليته الإنسانية النبيلة.
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-[#dfe6d5] pt-4 flex flex-wrap items-center justify-between gap-3 text-xs">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block">تاريخ الإصدار:</span>
                                    <span class="font-bold text-slate-700">{{ $cert->issued_at->translatedFormat('d F Y') }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="window.print()"
                                        class="rounded-xl bg-[#31421e] px-4 py-2 text-xs font-bold text-white hover:bg-[#52643a] transition cursor-pointer shadow-sm">
                                        🖨️ طباعة / حفظ PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm space-y-3">
                    <span class="text-4xl">📜</span>
                    <h3 class="text-base font-black text-slate-800">لا توجد شهادات مصدرة بعد</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                        عند إنجازك للمهام التطوعية، يمكنك في أي وقت طلب إصدار شهادة تطوع رقمية موثقة من هذا القسم.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('provider.available') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#31421e] px-6 py-3 text-xs font-bold text-white shadow-sm hover:bg-[#52643a] transition">
                            <span>استعراض الفرص التطوعية المتاحة</span>
                            <span>←</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
