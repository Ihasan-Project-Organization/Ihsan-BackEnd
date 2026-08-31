<script>
window.openCreateRequestModal = function() {
    window.dispatchEvent(new CustomEvent('open-create-request-modal'));
};
</script>

{{-- مودال إنشاء طلب مساعدة جديد (3 خطوات) مصغر ومتناسق تماماً مع شاشات الحاسوب والموبايل --}}
<div x-data="{
    open: false,
    step: 1,
    title: '',
    description: '',
    location: {{ json_encode(auth()->user()?->registrationProfile?->address ?? '') }},
    scheduled_at: '',
    serviceTypes: [
        { label: '💊 شراء دواء', val: 'شراء دواء من الصيدلية' },
        { label: '🛒 أغراض منزلية', val: 'شراء أغراض واحتياجات منزلية' },
        { label: '🏥 مرافقة طبية', val: 'مرافقة طبية إلى العيادة' },
        { label: '🤝 زيارة ودية', val: 'زيارة اجتماعية وقراءة' },
        { label: '🧹 مساعدة منزلية', val: 'مساعدة في ترتيب احتياجات منزلية' },
        { label: '📄 معاملات ورقية', val: 'مساعدة في مراجعة أوراق رسمية' }
    ],
    setPreset(val) {
        this.title = val;
    },
    nextStep() {
        if (this.step === 1) {
            if (!this.title.trim() || !this.description.trim()) {
                alert('يرجى كتابة عنوان الطلب والشرح.');
                return;
            }
            this.step = 2;
        } else if (this.step === 2) {
            if (!this.scheduled_at || !this.location.trim()) {
                alert('يرجى تحديد موعد التنفيذ والموقع.');
                return;
            }
            this.step = 3;
        }
    },
    prevStep() {
        if (this.step > 1) {
            this.step--;
        }
    },
    resetForm() {
        this.step = 1;
        this.title = '';
        this.description = '';
        this.scheduled_at = '';
        this.open = false;
    },
    init() {
        window.addEventListener('open-create-request-modal', () => {
            this.open = true;
        });
    }
}"
x-on:open-create-request-modal.window="open = true"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    {{-- خلفية التعتيم Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="resetForm()"></div>

    {{-- محتوى المودال المتناسق والمصغر للحاسوب --}}
    <div class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl transition-all my-auto max-h-[92vh] flex flex-col">
        
        {{-- رأس المودال ومؤشر الخطوات مع زر إغلاق سريع --}}
        <div class="relative text-center pb-2 border-b border-slate-100 shrink-0">
            <button type="button" @click="resetForm()" class="absolute left-0 top-0 p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-lg sm:text-xl font-black text-[#31421e]">طلب مساعدة جديد</h3>

            {{-- مؤشر الخطوات المصغر 1 - 2 - 3 --}}
            <div class="mt-3 flex items-center justify-center gap-2 sm:gap-3">
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full text-xs font-bold transition"
                    :class="step >= 1 ? 'bg-[#31421e] text-white shadow-sm' : 'bg-slate-100 text-slate-400'">
                    1
                </div>
                <div class="h-0.5 w-6 sm:w-8 rounded-full" :class="step >= 2 ? 'bg-[#31421e]' : 'bg-slate-200'"></div>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full text-xs font-bold transition"
                    :class="step >= 2 ? 'bg-[#31421e] text-white shadow-sm' : 'bg-slate-100 text-slate-400'">
                    2
                </div>
                <div class="h-0.5 w-6 sm:w-8 rounded-full" :class="step >= 3 ? 'bg-[#31421e]' : 'bg-slate-200'"></div>
                <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full text-xs font-bold transition"
                    :class="step === 3 ? 'bg-[#31421e] text-white shadow-sm' : 'bg-slate-100 text-slate-400'">
                    3
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('service-requests.store') }}" class="mt-4 overflow-y-auto pr-1">
            @csrf

            {{-- الخطوة 1: اشرح ما تحتاجه --}}
            <div x-show="step === 1" class="space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700">نوع الخدمة / عنوان الطلب</label>
                    <input type="text" name="title" x-model="title" required
                        placeholder="مثال: شراء دواء، مرافقة طبية..."
                        class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#718256] focus:ring-[#718256]">
                </div>

                {{-- اختيارات سريعة مصغرة --}}
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 mb-1.5">اقتراحات سريعة:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="item in serviceTypes" :key="item.label">
                            <button type="button" @click="setPreset(item.val)"
                                class="rounded-lg border border-[#dfe6d5] bg-[#f8faf6] px-2.5 py-1 text-[11px] font-bold text-[#31421e] hover:bg-[#eef2e8] transition">
                                <span x-text="item.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">شرح الطلب بالتفصيل</label>
                    <textarea name="description" x-model="description" rows="3" required
                        placeholder="مثال: أحتاج دواء الضغط من الصيدلية القريبة مع إحضار الفاتورة..."
                        class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2 text-xs sm:text-sm leading-5 focus:border-[#718256] focus:ring-[#718256]"></textarea>
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="button" @click="nextStep()"
                        class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                        التالي
                    </button>
                    <button type="button" @click="resetForm()"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </div>

            {{-- الخطوة 2: موعد التنفيذ والموقع --}}
            <div x-show="step === 2" class="space-y-3.5" style="display: none;">
                <div>
                    <label class="block text-xs font-bold text-slate-700">موعد تنفيذ الخدمة</label>
                    <input type="datetime-local" name="scheduled_at" x-model="scheduled_at" required
                        class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#718256] focus:ring-[#718256]">
                    <p class="mt-1 text-[11px] text-slate-400">حدد التاريخ والوقت وسيتولى النظام إدارة المهل تلقائيًا.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700">الموقع / العنوان</label>
                    <input type="text" name="location" x-model="location" required
                        placeholder="مثال: حي الرمال، شارع الوحدة"
                        class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#718256] focus:ring-[#718256]">
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="button" @click="nextStep()"
                        class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                        التالي
                    </button>
                    <button type="button" @click="prevStep()"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        السابق
                    </button>
                </div>
            </div>

            {{-- الخطوة 3: مراجعة وتأكيد النشر --}}
            <div x-show="step === 3" class="space-y-3.5" style="display: none;">
                <div class="rounded-xl border border-[#dfe6d5] bg-[#f8faf6] p-3.5 text-right space-y-2.5 text-xs">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400">عنوان الطلب:</span>
                        <p class="font-extrabold text-[#31421e]" x-text="title"></p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400">الوصف:</span>
                        <p class="text-[11px] leading-5 text-slate-700" x-text="description"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-200/60 pt-2 text-[11px]">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400">الموعد:</span>
                            <p class="font-bold text-slate-800" x-text="scheduled_at"></p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400">الموقع:</span>
                            <p class="font-bold text-slate-800" x-text="location"></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-2.5 text-[11px] text-emerald-800 flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>سيتم نشر الطلب فوراً للمتطوعين ومقدمي الخدمة المناسبين لمنطقتك.</span>
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                        نشر الطلب الآن
                    </button>
                    <button type="button" @click="prevStep()"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        السابق
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
