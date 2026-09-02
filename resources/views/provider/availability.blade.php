<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">

        {{-- رأس الصفحة --}}
        <div>
            <h1 class="text-2xl font-black text-[#31421e] sm:text-3xl">التوفر والإعدادات</h1>
            <p class="mt-1 text-xs text-slate-500 sm:text-sm">حدد متى وأين ترغب في تقديم الخدمات والمساعدة لكبار السن.</p>
        </div>

        {{-- تنبيه نجاح الحفظ --}}
        @if (session('status') === 'settings-updated')
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-xs font-bold flex items-center justify-between">
                <span>✓ تم حفظ إعدادات التوفر وجدول العمل بنجاح.</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600">✕</button>
            </div>
        @endif

        {{-- نموذج التوفر والخدمات (مطابق لصفحة 21 بالملف) --}}
        <form method="POST" action="{{ route('provider.availability.update') }}" class="grid gap-6 lg:grid-cols-2">
            @csrf

            {{-- 1. جدول التوفر الأسبوعي --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                <div>
                    <span class="text-xs font-bold text-slate-400">الجدول الزمني</span>
                    <h2 class="text-lg font-black text-[#31421e] mt-1">جدول التوفر الأسبوعي</h2>
                </div>

                {{-- أيام العمل --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">أيام تقديم الخدمة</label>
                    @php($days = [
                        'sat' => 'السبت',
                        'sun' => 'الأحد',
                        'mon' => 'الإثنين',
                        'tue' => 'الثلاثاء',
                        'wed' => 'الأربعاء',
                        'thu' => 'الخميس',
                        'fri' => 'الجمعة',
                    ])
                    @php($selectedDays = $setting->available_days ?? ['sat', 'sun', 'mon', 'tue', 'wed', 'thu'])

                    <div class="grid grid-cols-4 gap-2 sm:grid-cols-7">
                        @foreach ($days as $key => $dayName)
                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border p-3 text-center transition {{ in_array($key, $selectedDays) ? 'border-[#52643a] bg-[#eef2e8] text-[#31421e] font-black' : 'border-slate-200 bg-slate-50 text-slate-500 font-medium hover:bg-slate-100' }}">
                                <input type="checkbox" name="available_days[]" value="{{ $key }}" class="sr-only" {{ in_array($key, $selectedDays) ? 'checked' : '' }}>
                                <span class="text-xs">{{ $dayName }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- ساعات العمل --}}
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">من الساعة</label>
                        <input type="time" name="available_from" value="{{ substr($setting->available_from ?? '08:00:00', 0, 5) }}" required
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">إلى الساعة</label>
                        <input type="time" name="available_to" value="{{ substr($setting->available_to ?? '18:00:00', 0, 5) }}" required
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                    </div>
                </div>

                {{-- حالة التوفر المؤقتة --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">حالة التوفر الحالية</label>
                    <select name="is_available" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="1" {{ $setting->is_available ? 'selected' : '' }}>🟢 متاح لاستقبال الطلبات حسب الجدول</option>
                        <option value="0" {{ !$setting->is_available ? 'selected' : '' }}>🔴 غير متاح مؤقتاً (إجازة / توقف مؤقت)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-2xl bg-[#31421e] py-3 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                        حفظ جدول التوفر
                    </button>
                </div>
            </div>

            {{-- 2. الخدمات والنطاق الجغرافي --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                <div>
                    <span class="text-xs font-bold text-slate-400">التخصيص والنطاق</span>
                    <h2 class="text-lg font-black text-[#31421e] mt-1">الخدمات والمنطقة الجغرافية</h2>
                </div>

                {{-- الخدمات التي أقدمها --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">نوع الخدمات التي ترغب بتقديمها</label>
                    @php($servicesList = [
                        'grocery' => ['title' => 'شراء أغراض منزلية', 'icon' => '🛒'],
                        'medical_escort' => ['title' => 'مرافقة إلى موعد طبي', 'icon' => '🚶‍♂️'],
                        'medicine' => ['title' => 'إحضار دواء من الصيدلية', 'icon' => '💊'],
                        'home_help' => ['title' => 'مساعدة منزلية خفيفة', 'icon' => '🧹'],
                    ])
                    @php($selectedServices = $setting->offered_services ?? ['grocery', 'medical_escort', 'medicine', 'home_help'])

                    <div class="space-y-2">
                        @foreach ($servicesList as $key => $srv)
                            <label class="flex cursor-pointer items-center justify-between rounded-2xl border p-3.5 transition {{ in_array($key, $selectedServices) ? 'border-[#52643a] bg-[#f8faf6]' : 'border-slate-200 bg-slate-50' }}">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-lg">{{ $srv['icon'] }}</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $srv['title'] }}</span>
                                </div>
                                <input type="checkbox" name="offered_services[]" value="{{ $key }}" class="h-4 w-4 rounded border-slate-300 text-[#31421e] focus:ring-[#52643a]" {{ in_array($key, $selectedServices) ? 'checked' : '' }}>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- المنطقة والمدينة --}}
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">المدينة الرئيسية</label>
                        <select name="service_city" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                            <option value="مدينة غزة" {{ $setting->service_city === 'مدينة غزة' ? 'selected' : '' }}>مدينة غزة</option>
                            <option value="شمال غزة" {{ $setting->service_city === 'شمال غزة' ? 'selected' : '' }}>شمال غزة (جباليا، بيت لاهيا)</option>
                            <option value="المنطقة الوسطى" {{ $setting->service_city === 'المنطقة الوسطى' ? 'selected' : '' }}>المنطقة الوسطى (دير البلح، النصيرات)</option>
                            <option value="خانيونس" {{ $setting->service_city === 'خانيونس' ? 'selected' : '' }}>خانيونس</option>
                            <option value="رفح" {{ $setting->service_city === 'رفح' ? 'selected' : '' }}>رفح</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">نطاق الوصول (المسافة)</label>
                        <select name="coverage_radius_km" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                            <option value="3" {{ $setting->coverage_radius_km === 3 ? 'selected' : '' }}>حتى 3 كم</option>
                            <option value="5" {{ $setting->coverage_radius_km === 5 ? 'selected' : '' }}>حتى 5 كم</option>
                            <option value="10" {{ $setting->coverage_radius_km === 10 ? 'selected' : '' }}>حتى 10 كم</option>
                            <option value="15" {{ $setting->coverage_radius_km === 15 ? 'selected' : '' }}>حتى 15 كم</option>
                            <option value="20" {{ $setting->coverage_radius_km === 20 ? 'selected' : '' }}>حتى 20 كم</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl bg-[#eef2e8] p-3.5 border border-[#dfe6d5] text-[11px] text-[#31421e]">
                    <span>ℹ️</span> <strong>قاعدة النظام:</strong> المطابقة تتم تلقائياً بحسب نوع الخدمة والمنطقة ونطاق الوصول المحددين هنا.
                </div>
            </div>

        </form>

        {{-- 3. قاموس الحالات التشغيلية الكامل (مطابق لصفحة 21 و 22 بالملف) --}}
        <div class="space-y-4">
            <div>
                <h2 class="text-xl font-black text-[#31421e]">قاموس الحالات التشغيلية</h2>
                <p class="text-xs text-slate-500">مرجع مختصر لكل حالة يمكن أن تظهر داخل المنصة ودلالتها الفنية.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @php($statusDictionary = [
                    ['title' => 'طلب متاح', 'desc' => 'مفتوح للقبول من المتطوعين', 'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                    ['title' => 'تم القبول', 'desc' => 'أُسند لمقدم الخدمة حصرياً', 'bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
                    ['title' => 'طلب قادم', 'desc' => 'ينتظر حلول الموعد المحدد', 'bg' => 'bg-sky-50', 'text' => 'text-sky-800', 'border' => 'border-sky-200'],
                    ['title' => 'في الطريق', 'desc' => 'بدأ المتطوع التوجه للموقع', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-800', 'border' => 'border-indigo-200'],
                    ['title' => 'متأخر', 'desc' => 'تجاوز وقت الوصول المتوقع', 'bg' => 'bg-red-50', 'text' => 'text-red-800', 'border' => 'border-red-200'],
                    ['title' => 'وصل', 'desc' => 'تم تسجيل وتأكيد الوصول', 'bg' => 'bg-teal-50', 'text' => 'text-teal-800', 'border' => 'border-teal-200'],
                    ['title' => 'قيد التنفيذ', 'desc' => 'الخدمة جارية ومباشرة', 'bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'border' => 'border-purple-200'],
                    ['title' => 'بانتظار التأكيد', 'desc' => 'أنهى المتطوع وأرسل الملخص', 'bg' => 'bg-orange-50', 'text' => 'text-orange-800', 'border' => 'border-orange-200'],
                    ['title' => 'مكتمل', 'desc' => 'أكد كبير السن وتم التقييم', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
                    ['title' => 'تم الاعتذار', 'desc' => 'انسحب المتطوع لظرف طارئ', 'bg' => 'bg-rose-50', 'text' => 'text-rose-800', 'border' => 'border-rose-200'],
                    ['title' => 'البحث عن بديل', 'desc' => 'إعادة نشر الطلب بصورة عاجلة', 'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                    ['title' => 'تحت المراجعة', 'desc' => 'اعتراض أو مشكلة يراجعها الدعم', 'bg' => 'bg-slate-50', 'text' => 'text-slate-800', 'border' => 'border-slate-200'],
                ])

                @foreach ($statusDictionary as $item)
                    <div class="rounded-2xl {{ $item['bg'] }} p-4 border {{ $item['border'] }}">
                        <h4 class="text-xs font-black {{ $item['text'] }}">{{ $item['title'] }}</h4>
                        <p class="mt-1 text-[11px] font-semibold text-slate-600">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>

