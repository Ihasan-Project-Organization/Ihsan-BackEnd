<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- رأس الصفحة --}}
        <div>
            <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">إعدادات التوفر والتشغيل</h1>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">التحكم في جاهزيتك لاستقبال طلبات المساعدة ومراجعة القواعد التشغيلية المعتمدة.</p>
        </div>

        {{-- تنبيه نجاح الحفظ --}}
        @if (session('status') === 'settings-updated')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between animate-fadeIn">
                <span>✓ تم حفظ وتحديث حالة التوفر بنجاح.</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs font-bold text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
            </div>
        @endif

        {{-- القسم العلوي: بطاقة ضبط التوفر + القواعد التشغيلية --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- 1. ضبط حالة التوفر الحقيقية --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                <div>
                    <span class="text-xs font-bold text-slate-400">الجاهزية التشغيلية</span>
                    <h2 class="text-lg font-black text-[#31421e] mt-1">حالة التوفر الحالية</h2>
                    <p class="text-xs text-slate-500 mt-1">تتحكم هذه الحالة في ظهور حسابك واستقبالك للطلبات الجديدة في قائمة الطلبات المتاحة.</p>
                </div>

                <form method="POST" action="{{ route('provider.availability.update') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-3">
                        <label class="relative flex cursor-pointer items-start gap-4 rounded-2xl border-2 p-4 transition {{ $setting?->is_available ? 'border-[#31421e] bg-[#f8faf6]' : 'border-slate-200 bg-slate-50 hover:bg-slate-100' }}">
                            <input type="radio" name="is_available" value="1" class="mt-1 h-4 w-4 border-slate-300 text-[#31421e] focus:ring-[#52643a]" {{ $setting?->is_available ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-black text-slate-900 flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>🟢 متاح لاستقبال الطلبات</span>
                                </span>
                                <p class="text-xs text-slate-500 mt-1 leading-5">
                                    حسابك نشط وجاهز. ستتمكن من تصفح وقبول كافة طلبات المساعدة المتوافقة مع مستواك.
                                </p>
                            </div>
                        </label>

                        <label class="relative flex cursor-pointer items-start gap-4 rounded-2xl border-2 p-4 transition {{ !$setting?->is_available ? 'border-rose-400 bg-rose-50/40' : 'border-slate-200 bg-slate-50 hover:bg-slate-100' }}">
                            <input type="radio" name="is_available" value="0" class="mt-1 h-4 w-4 border-slate-300 text-[#31421e] focus:ring-[#52643a]" {{ !$setting?->is_available ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-black text-slate-900 flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                                    <span>🔴 غير متاح مؤقتًا (إجازة / توقف)</span>
                                </span>
                                <p class="text-xs text-slate-500 mt-1 leading-5">
                                    يمكنك إيقاف الاستقبال مؤقتاً عند انشغالك أو سفرك دون أي تأثير على تقييمك أو مستواك.
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full rounded-2xl bg-[#31421e] py-3 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                            حفظ حالة التوفر
                        </button>
                    </div>
                </form>
            </div>

            {{-- 2. القواعد التشغيلية المعتمدة للمنصة (§4.2 من المرجع) --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                <div>
                    <span class="text-xs font-bold text-slate-400">نظام المنصة</span>
                    <h2 class="text-lg font-black text-[#31421e] mt-1">قواعد استقبال وتوزيع الطلبات</h2>
                    <p class="text-xs text-slate-500 mt-1">الضوابط والسياسات المعتمدة لإسناد وتنسيق المهام في منصة إحسان.</p>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5] flex items-start gap-3">
                        <span class="text-lg">📢</span>
                        <div>
                            <h4 class="font-black text-[#31421e]">البث المباشر (Broadcast):</h4>
                            <p class="text-slate-600 mt-0.5 leading-5">يتم عرض الطلبات المنشورة لجميع مقدمي الخدمة المؤهلين بحسب المستوى (Tier) وتفضيل الجنس.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5] flex items-start gap-3">
                        <span class="text-lg">⚡</span>
                        <div>
                            <h4 class="font-black text-[#31421e]">أسبقية القبول:</h4>
                            <p class="text-slate-600 mt-0.5 leading-5">أول مقدم خدمة يضغط "قبول الطلب" يفوز به، ويُقفل الطلب فوراً أمام البقية لمنع أي تضارب.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5] flex items-start gap-3">
                        <span class="text-lg">📍</span>
                        <div>
                            <h4 class="font-black text-[#31421e]">المرونة المكانية:</h4>
                            <p class="text-slate-600 mt-0.5 leading-5">يعرض كل طلب عنواناً نصياً واضحاً، ومقدم الخدمة يحدد بنفسه ملاءمة الموقع لوقته وقدرته.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- 3. قاموس الحالات التشغيلية المعتمد (القسم 4.3 من المرجع الشامل) --}}
        <div class="space-y-4">
            <div>
                <h2 class="text-xl font-black text-[#31421e]">قاموس الحالات التشغيلية المعتمد</h2>
                <p class="text-xs text-slate-500">المرجع الحصري المعتمد لجميع حالات الطلبات في منصة إحسان ومعانيها الميدانية.</p>
            </div>

            @php($officialStatuses = [
                ['name' => 'pending_acceptance', 'title' => 'بانتظار القبول', 'desc' => 'طلب متاح منشور لجميع مقدمي الخدمة المؤهلين.', 'badge' => 'bg-amber-50 text-amber-800 border-amber-200'],
                ['name' => 'accepted', 'title' => 'تم القبول', 'desc' => 'وافق مقدم خدمة مبدئياً على تقديم الخدمة.', 'badge' => 'bg-blue-50 text-blue-800 border-blue-200'],
                ['name' => 'assigned', 'title' => 'تم التوكيل الرسمي', 'desc' => 'تم ربط الطلب نهائياً وتأكيد الموعد وإظهار الهاتف.', 'badge' => 'bg-indigo-50 text-indigo-800 border-indigo-200'],
                ['name' => 'in_progress', 'title' => 'قيد التنفيذ', 'desc' => 'بدأ مقدم الخدمة تقديم المساعدة ميدانياً.', 'badge' => 'bg-purple-50 text-purple-800 border-purple-200'],
                ['name' => 'pending_confirmation', 'title' => 'بانتظار التأكيد', 'desc' => 'أنهى مقدم الخدمة المهمة وبانتظار تأكيد المستفيد.', 'badge' => 'bg-orange-50 text-orange-800 border-orange-200'],
                ['name' => 'completed', 'title' => 'مكتمل نهائياً', 'desc' => 'أكّد كبير السن الإكمال وتم التقييم الإجباري.', 'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                ['name' => 'provider_delayed', 'title' => 'تأخر مقدم الخدمة', 'desc' => 'حل موعد الخدمة ولم يبدأ التنفيذ أو تم الإبلاغ عن تأخير.', 'badge' => 'bg-amber-100 text-amber-900 border-amber-300'],
                ['name' => 'provider_apologized', 'title' => 'اعتذار مقدم الخدمة', 'desc' => 'اعتذر مقدم الخدمة بعد التوكيل وأعيد نشر الطلب.', 'badge' => 'bg-rose-50 text-rose-800 border-rose-200'],
                ['name' => 'no_provider_found', 'title' => 'لم يتوفر متطوع', 'desc' => 'حل موعد التنفيذ دون قبول أي متطوع للطلب.', 'badge' => 'bg-slate-100 text-slate-800 border-slate-200'],
                ['name' => 'under_review', 'title' => 'تحت المراجعة', 'desc' => 'أبلغ المستفيد عن مشكلة وجارٍ فحصها إدارياً.', 'badge' => 'bg-red-50 text-red-800 border-red-200'],
                ['name' => 'cancelled', 'title' => 'ملغى نهائياً', 'desc' => 'أُلغي الطلب بطلب كبير السن أو الإدارة.', 'badge' => 'bg-slate-200 text-slate-700 border-slate-300'],
            ])

            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($officialStatuses as $st)
                    <div class="rounded-2xl {{ $st['badge'] }} p-4 border flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black">{{ $st['title'] }}</h4>
                                <span class="font-mono text-[9px] opacity-70">{{ $st['name'] }}</span>
                            </div>
                            <p class="mt-1.5 text-[11px] leading-4 opacity-90">{{ $st['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
