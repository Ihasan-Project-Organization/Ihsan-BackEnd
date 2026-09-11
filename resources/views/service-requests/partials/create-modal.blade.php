<script>
window.openCreateRequestModal = function() {
    window.dispatchEvent(new CustomEvent('open-create-request-modal'));
};
</script>

{{-- مودال إنشاء طلب مساعدة جديد متعدد الخطوات بنظام تصميم إحسان المعتمد --}}
<div x-data="{
    open: false,
    step: 1,
    service_type: 'grocery',
    title: 'شراء أغراض منزلية',
    description: '',
    timing_type: 'scheduled',
    scheduled_at: '',
    gender_preference: 'any',
    pricing_type: 'volunteer',
    proposed_price: '',
    location: '',
    attachmentsCount: 0,

    services: [
        { id: 'grocery', title: 'شراء أغراض', desc: 'احتياجاتك المنزلية اليومية', icon: 'fa-cart-shopping' },
        { id: 'medicine', title: 'شراء دواء', desc: 'إحضار الأدوية من الصيدلية', icon: 'fa-kit-medical' },
        { id: 'medical_escort', title: 'مرافقة', desc: 'مرافقة لموعد أو مشوار', icon: 'fa-person-walking' },
        { id: 'social_visit', title: 'زيارة اجتماعية', desc: 'قضاء وقت لطيف ومؤانسة', icon: 'fa-users' },
        { id: 'home_help', title: 'مساعدة منزلية', desc: 'ترتيب واحتياجات منزلية', icon: 'fa-broom' },
        { id: 'support_request', title: 'طلب الدعم', desc: 'تواصل مع جهة داعمة أو خدمة أخرى', icon: 'fa-handshake-angle' }
    ],

    errorMessage: '',

    selectService(srv) {
        this.service_type = srv.id;
        this.title = srv.title;
        this.errorMessage = '';
    },

    nextStep() {
        this.errorMessage = '';
        if (this.step === 1) {
            if (!this.title.trim()) {
                this.errorMessage = 'يرجى كتابة أو اختيار عنوان للخدمة.';
                return;
            }
            if (!this.description.trim()) {
                this.errorMessage = 'يرجى كتابة شرح مختصر لما تحتاجه.';
                return;
            }
            this.step = 2;
        } else if (this.step === 2) {
            if (this.timing_type === 'scheduled' && !this.scheduled_at) {
                this.errorMessage = 'يرجى تحديد موعد وتاريخ تنفيذ الخدمة.';
                return;
            }
            if (this.pricing_type === 'paid' && (!this.proposed_price || this.proposed_price <= 0)) {
                this.errorMessage = 'يرجى تحديد السعر المقترح بالرقم.';
                return;
            }
            if (!this.location.trim()) {
                this.errorMessage = 'يرجى إدخال العنوان أو موقع التنفيذ.';
                return;
            }
            this.step = 3;
        }
    },

    prevStep() {
        this.errorMessage = '';
        if (this.step > 1) {
            this.step--;
        }
    },

    resetForm() {
        this.step = 1;
        this.errorMessage = '';
        this.service_type = 'grocery';
        this.title = 'شراء أغراض منزلية';
        this.description = '';
        this.timing_type = 'scheduled';
        this.scheduled_at = '';
        this.gender_preference = 'any';
        this.pricing_type = 'volunteer';
        this.proposed_price = '';
        this.location = '';
        this.attachmentsCount = 0;
        this.open = false;
    },

    handleFileChange(e) {
        this.attachmentsCount = e.target.files ? e.target.files.length : 0;
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
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto"
style="font-family: 'Alexandria', sans-serif;">

    {{-- خلفية التعتيم Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="resetForm()"></div>

    {{-- بطاقة المودال الرئيسية المتناسقة مع هوية الموقع --}}
    <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-3xl bg-[#fbf9f5] border border-[#e2dcd0] p-5 sm:p-7 shadow-2xl transition-all my-auto max-h-[92vh] flex flex-col text-slate-800">
        
        {{-- رأس المودال ومؤشر الخطوات البصري --}}
        <div class="relative pb-4 border-b border-[#e2dcd0] shrink-0">
            <button type="button" @click="resetForm()" 
                class="absolute left-0 top-0 p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-200/60 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <div class="text-center">
                <span class="text-xs font-semibold text-[#6b7280]">لوحة التحكم</span>
                <h3 class="text-xl sm:text-2xl font-black text-[#3b5228] mt-0.5">طلب مساعدة جديد</h3>
                <p class="text-xs text-[#6b7280] mt-1">اختار/ي نوع الخدمة وسنجد لك الشخص الأنسب لمساعدتك</p>
            </div>

            {{-- مؤشر الخطوات الأنيق (1 نوع الخدمة - 2 التفاصيل والموقع - 3 المراجعة والتأكيد) --}}
            <div class="mt-4 flex items-center justify-center gap-2 sm:gap-4 max-w-sm mx-auto">
                <div class="flex items-center gap-1.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition"
                        :class="step >= 1 ? 'bg-[#3b5228] text-white shadow-sm' : 'bg-[#e2dcd0] text-slate-500'">1</span>
                    <span class="text-xs font-bold" :class="step >= 1 ? 'text-[#3b5228]' : 'text-slate-400'">الخدمة</span>
                </div>
                <div class="h-0.5 w-6 sm:w-10 rounded-full transition" :class="step >= 2 ? 'bg-[#3b5228]' : 'bg-[#e2dcd0]'"></div>
                <div class="flex items-center gap-1.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition"
                        :class="step >= 2 ? 'bg-[#3b5228] text-white shadow-sm' : 'bg-[#e2dcd0] text-slate-500'">2</span>
                    <span class="text-xs font-bold" :class="step >= 2 ? 'text-[#3b5228]' : 'text-slate-400'">التفاصيل</span>
                </div>
                <div class="h-0.5 w-6 sm:w-10 rounded-full transition" :class="step >= 3 ? 'bg-[#3b5228]' : 'bg-[#e2dcd0]'"></div>
                <div class="flex items-center gap-1.5">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold transition"
                        :class="step === 3 ? 'bg-[#3b5228] text-white shadow-sm' : 'bg-[#e2dcd0] text-slate-500'">3</span>
                    <span class="text-xs font-bold" :class="step === 3 ? 'text-[#3b5228]' : 'text-slate-400'">التأكيد</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('service-requests.store') }}" enctype="multipart/form-data" class="mt-4 overflow-y-auto pr-1">
            @csrf

            {{-- رسالة خطأ التحقق المضمنة داخل الواجهة --}}
            <div x-show="errorMessage" x-cloak
                class="mb-3 rounded-xl bg-rose-50 border border-rose-200 p-2.5 text-xs text-rose-800 font-bold flex items-center gap-2 animate-fadeIn">
                <i class="fa-solid fa-circle-exclamation text-rose-600 shrink-0 text-sm"></i>
                <span x-text="errorMessage"></span>
            </div>

            {{-- ════════════════ الخطوة 1: اختيار نوع الخدمة والشرح ════════════════ --}}
            <div x-show="step === 1" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">اختر نوع الخدمة التي تحتاجها:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3">
                        <template x-for="srv in services" :key="srv.id">
                            <div @click="selectService(srv)"
                                class="p-3.5 rounded-2xl border text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center min-h-[110px]"
                                :class="service_type === srv.id 
                                    ? 'bg-white border-[#3b5228] shadow-md ring-2 ring-[#3b5228]/20 scale-[1.02]' 
                                    : 'bg-white/80 border-[#e2dcd0] hover:bg-white hover:border-[#b8c7a8]'">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2 transition"
                                    :class="service_type === srv.id ? 'bg-[#3b5228] text-white' : 'bg-[#f2ede4] text-[#3b5228]'">
                                    <i :class="'fa-solid ' + srv.icon + ' text-base'"></i>
                                </div>
                                <h4 class="text-xs sm:text-sm font-bold text-[#3b5228]" x-text="srv.title"></h4>
                                <p class="text-[10px] sm:text-[11px] text-[#6b7280] mt-0.5 line-clamp-1" x-text="srv.desc"></p>
                            </div>
                        </template>
                    </div>
                    <input type="hidden" name="service_type" :value="service_type">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">عنوان الطلب المخصص</label>
                    <input type="text" name="title" x-model="title" required
                        placeholder="مثال: شراء دواء السكري من الصيدلية، مرافقة لمستشفى الشفاء..."
                        class="w-full rounded-xl border-[#e2dcd0] bg-white px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#3b5228] focus:ring-1 focus:ring-[#3b5228]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">شرح وتفاصيل ما تحتاجه</label>
                    <textarea name="description" x-model="description" rows="3" required
                        placeholder="اشرح طلبك بوضوح: نوع الاحتياج، أي ملاحظات خاصة تساعد مقدم الخدمة على فهم المطلوب بدقة..."
                        class="w-full rounded-xl border-[#e2dcd0] bg-white px-3.5 py-2.5 text-xs sm:text-sm leading-5 focus:border-[#3b5228] focus:ring-1 focus:ring-[#3b5228]"></textarea>
                </div>

                {{-- تنبيه الخصوصية من تصميم الفرونت إند --}}
                <div class="rounded-2xl bg-[#f2ede4]/70 border border-[#e2dcd0] p-3 text-[11px] text-slate-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#3b5228]/10 flex items-center justify-center shrink-0 text-[#3b5228]">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-[#3b5228] block">خصوصيتك أولويتنا</span>
                        <p class="text-slate-600 text-[10px] sm:text-[11px]">جميع المتطوعين موثوقون ولن نشارك بياناتك إلا مع الشخص الذي يقبل طلبك.</p>
                    </div>
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="button" @click="nextStep()"
                        class="flex-1 rounded-xl bg-[#3b5228] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#4e6b35] transition cursor-pointer flex items-center justify-center gap-2">
                        <span>متابعة لتفاصيل الموعد والموقع</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                    <button type="button" @click="resetForm()"
                        class="rounded-xl border border-[#e2dcd0] bg-white px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </div>

            {{-- ════════════════ الخطوة 2: تفاصيل التوقيت، الجنس، المقابل، الموقع والمرفقات ════════════════ --}}
            <div x-show="step === 2" class="space-y-4" style="display: none;">
                
                {{-- 1. التوقيت (فوري أو مجدول) --}}
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-3.5 sm:p-4">
                    <label class="block text-xs font-bold text-[#3b5228] mb-2">
                        <i class="fa-regular fa-clock ml-1"></i> توقيت تنفيذ الطلب:
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="timing_type === 'immediate' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="timing_type" value="immediate" x-model="timing_type" class="accent-[#3b5228]">
                            <span>⚡ بدون مدة محددة (في أقرب وقت متاح)</span>
                        </label>
                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="timing_type === 'scheduled' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="timing_type" value="scheduled" x-model="timing_type" class="accent-[#3b5228]">
                            <span>📅 مجدول في موعد محدد</span>
                        </label>
                    </div>

                    {{-- منتقي التاريخ والوقت إذا كان مجدولاً --}}
                    <div x-show="timing_type === 'scheduled'" class="mt-3 pt-3 border-t border-slate-100">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">اختر موعد وتاريخ التنفيذ:</label>
                        <input type="datetime-local" name="scheduled_at" x-model="scheduled_at"
                            :required="timing_type === 'scheduled'"
                            class="w-full rounded-xl border-[#e2dcd0] bg-slate-50 px-3 py-2 text-xs sm:text-sm focus:border-[#3b5228] focus:ring-1 focus:ring-[#3b5228]">
                        <p class="text-[10px] text-slate-400 mt-1">حدد التاريخ والوقت وسيتولى النظام إدارة فترات التنبيه والمتابعة تلقائياً.</p>
                    </div>
                </div>

                {{-- 2. تفضيل جنس مقدم الخدمة --}}
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-3.5 sm:p-4">
                    <label class="block text-xs font-bold text-[#3b5228] mb-2">
                        <i class="fa-solid fa-venus-mars ml-1"></i> تفضيل جنس مقدم الخدمة (المتطوع):
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="gender_preference === 'any' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="gender_preference" value="any" x-model="gender_preference" class="accent-[#3b5228]">
                            <span>لا يهم (أي جنس)</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="gender_preference === 'male' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="gender_preference" value="male" x-model="gender_preference" class="accent-[#3b5228]">
                            <span>ذكر فقط</span>
                        </label>
                        <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="gender_preference === 'female' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="gender_preference" value="female" x-model="gender_preference" class="accent-[#3b5228]">
                            <span>أنثى فقط</span>
                        </label>
                    </div>
                </div>

                {{-- 3. تصنيف الخدمة والمقابل المالي --}}
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-3.5 sm:p-4">
                    <label class="block text-xs font-bold text-[#3b5228] mb-2">
                        <i class="fa-solid fa-hand-holding-dollar ml-1"></i> المقابل المالي للخدمة:
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="pricing_type === 'volunteer' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="pricing_type" value="volunteer" x-model="pricing_type" class="accent-[#3b5228]">
                            <span>🤲 عمل تطوعي (بدون مقابل)</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition text-xs font-bold"
                            :class="pricing_type === 'paid' ? 'bg-[#f2ede4] border-[#3b5228] text-[#3b5228]' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            <input type="radio" name="pricing_type" value="paid" x-model="pricing_type" class="accent-[#3b5228]">
                            <span>💵 خدمة مدفوعة (مكافأة مقترحة)</span>
                        </label>
                    </div>

                    {{-- حقل السعر المقترح إذا كانت الخدمة مدفوعة --}}
                    <div x-show="pricing_type === 'paid'" class="mt-3 pt-3 border-t border-slate-100">
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">المبلغ المقترح (شيكل):</label>
                        <div class="relative">
                            <input type="number" step="1" min="1" name="proposed_price" x-model="proposed_price"
                                placeholder="مثال: 30"
                                :required="pricing_type === 'paid'"
                                class="w-full rounded-xl border-[#e2dcd0] bg-slate-50 px-3 py-2 text-xs sm:text-sm focus:border-[#3b5228] focus:ring-1 focus:ring-[#3b5228] pl-12">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">₪ شيكل</span>
                        </div>
                    </div>
                </div>

                {{-- 4. موقع التنفيذ / العنوان النصي --}}
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-3.5 sm:p-4">
                    <label class="block text-xs font-bold text-[#3b5228] mb-1">
                        <i class="fa-solid fa-location-dot ml-1"></i> العنوان النصي وموقع التنفيذ:
                    </label>
                    <input type="text" name="location" x-model="location" required
                        placeholder="مثال: غزة، حي الرمال، بالقرب من صيدلية الشفاء، عمارة القدس"
                        class="w-full rounded-xl border-[#e2dcd0] bg-slate-50 px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#3b5228] focus:ring-1 focus:ring-[#3b5228]">
                    <p class="text-[10px] text-slate-400 mt-1">اذكر علامات مميزة قريبة لتسهيل وصول مقدم الخدمة لمنزلك بدقة.</p>
                </div>

                {{-- 5. المرفقات الاختيارية (صور / ملفات) --}}
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-3.5 sm:p-4">
                    <label class="block text-xs font-bold text-[#3b5228] mb-1">
                        <i class="fa-solid fa-paperclip ml-1"></i> مرفقات توضيحية (اختياري):
                    </label>
                    <label class="mt-1 flex flex-col items-center justify-center p-3 sm:p-4 border-2 border-dashed border-[#e2dcd0] rounded-2xl cursor-pointer hover:bg-slate-50 transition">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-[#3b5228] mb-1"></i>
                        <span class="text-xs font-bold text-slate-700">اضغط لرفع صور الوصفة الطبية، قائمة الأغراض، أو وثيقة</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">الملفات المدعومة: JPG, PNG, PDF (الحد الأقصى 5 ميجابايت)</span>
                        <input type="file" name="attachments[]" multiple @change="handleFileChange($event)" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                    </label>
                    <div x-show="attachmentsCount > 0" class="mt-2 text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>تم تحديد <span x-text="attachmentsCount"></span> ملف/ملفات بنجاح</span>
                    </div>
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="button" @click="nextStep()"
                        class="flex-1 rounded-xl bg-[#3b5228] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#4e6b35] transition cursor-pointer flex items-center justify-center gap-2">
                        <span>مراجعة الطلب والتأكيد</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                    <button type="button" @click="prevStep()"
                        class="rounded-xl border border-[#e2dcd0] bg-white px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        السابق
                    </button>
                </div>
            </div>

            {{-- ════════════════ الخطوة 3: مراجعة وتأكيد النشر ════════════════ --}}
            <div x-show="step === 3" class="space-y-4" style="display: none;">
                <div class="rounded-2xl border border-[#e2dcd0] bg-white p-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <span class="font-bold text-slate-400">نوع الخدمة والعنوان:</span>
                        <span class="font-extrabold text-[#3b5228]" x-text="title"></span>
                    </div>

                    <div>
                        <span class="font-bold text-slate-400 block mb-1">شرح الطلب:</span>
                        <p class="text-slate-700 leading-relaxed bg-[#fbf9f5] p-2.5 rounded-xl border border-[#e2dcd0]" x-text="description"></p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-1">
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400">التوقيت:</span>
                            <span class="font-bold text-slate-800" x-text="timing_type === 'immediate' ? 'فوري (30 دقيقة)' : scheduled_at"></span>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400">تفضيل الجنس:</span>
                            <span class="font-bold text-slate-800" x-text="gender_preference === 'male' ? 'ذكر' : (gender_preference === 'female' ? 'أنثى' : 'لا يهم')"></span>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 col-span-2 sm:col-span-1">
                            <span class="block text-[10px] font-bold text-slate-400">المقابل المالي:</span>
                            <span class="font-bold text-slate-800" x-text="pricing_type === 'paid' ? (proposed_price + ' ₪ شيكل') : 'تطوع خيري'"></span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-400">موقع التنفيذ:</span>
                        <span class="font-bold text-slate-800" x-text="location"></span>
                    </div>

                    <div x-show="attachmentsCount > 0" class="text-emerald-700 text-[11px] font-bold">
                        <i class="fa-solid fa-paperclip ml-1"></i> سيتم إرفاق <span x-text="attachmentsCount"></span> ملف/صورة مع الطلب.
                    </div>
                </div>

                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-800 flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg shrink-0"></i>
                    <span>سيتم نشر هذا الطلب فوراً للمتطوعين ومقدمي الخدمة المؤهلين في منطقتك.</span>
                </div>

                <div class="mt-5 flex gap-2.5 pt-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-[#3b5228] py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg hover:bg-[#4e6b35] transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>نشر الطلب الآن</span>
                    </button>
                    <button type="button" @click="prevStep()"
                        class="rounded-xl border border-[#e2dcd0] bg-white px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        السابق
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
