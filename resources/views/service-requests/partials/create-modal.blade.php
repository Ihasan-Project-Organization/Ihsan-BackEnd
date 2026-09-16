<script>
window.openCreateRequestModal = function () {
    window.dispatchEvent(new CustomEvent('open-create-request-modal'));
};
</script>

<div
    x-data="{
        open: false,
        step: 1,
        profileCity: @js(auth()->user()?->elderProfile?->city ?? ''),
        service_type: '',
        title: '',
        location: @js(auth()->user()?->elderProfile?->city ?? ''),
        timing_type: 'scheduled',
        scheduled_at: '',
        description: '',
        errorMessage: '',
        services: [
            { id: 'grocery', title: 'شراء أغراض', description: 'احتياجات المنزل اليومية', icon: 'fa-cart-shopping' },
            { id: 'medicine', title: 'شراء دواء', description: 'إحضار الدواء من الصيدلية', icon: 'fa-kit-medical' },
            { id: 'medical_escort', title: 'مرافقة طبية', description: 'مرافقة إلى طبيب أو مستشفى', icon: 'fa-person-walking' },
            { id: 'social_visit', title: 'زيارة اجتماعية', description: 'زيارة ودية ومؤانسة', icon: 'fa-users' },
            { id: 'home_help', title: 'مساعدة منزلية', description: 'مساعدة خفيفة داخل المنزل', icon: 'fa-broom' },
            { id: 'support_request', title: 'خدمة أخرى', description: 'احتياج آخر غير موجود بالقائمة', icon: 'fa-hand-holding-heart' }
        ],
        stepLabels: ['الخدمة', 'المكان', 'الموعد', 'التفاصيل', 'التأكيد'],
        stepTitles: [
            'ما نوع الخدمة التي تحتاجها؟',
            'أين تريد تنفيذ الخدمة؟',
            'متى تحتاج الخدمة؟',
            'هل لديك تفاصيل إضافية؟',
            'راجع طلبك قبل الإرسال'
        ],
        selectService(service) {
            this.service_type = service.id;
            this.title = service.title;
            this.errorMessage = '';
            window.IhsanVoice?.playFixed('service_' + service.id, service.title);
        },

        useProfileCity() {
            this.location = this.profileCity;
            this.errorMessage = '';
        },
        nextStep() {
            this.errorMessage = '';
            if (this.step === 1 && !this.service_type) {
                this.errorMessage = 'اختر نوع الخدمة أولًا.';
                return;
            }
            if (this.step === 2 && !this.location.trim()) {
                this.errorMessage = 'اكتب المنطقة أو العنوان الذي تريد تنفيذ الخدمة فيه.';
                return;
            }
            if (this.step === 3) {
                if (!this.scheduled_at) {
                    this.errorMessage = 'اختر اليوم والساعة.';
                    return;
                }
                if (new Date(this.scheduled_at) <= new Date()) {
                    this.errorMessage = 'اختر موعدًا صحيحًا في المستقبل.';
                    return;
                }
            }
            if (this.step === 4 && !this.description.trim()) {
                this.description = 'أحتاج إلى خدمة ' + this.title;
            }
            if (this.step < 5) this.step++;
        },
        prevStep() {
            this.errorMessage = '';
            if (this.step > 1) this.step--;
        },
        skipDetails() {
            this.description = 'أحتاج إلى خدمة ' + this.title;
            this.nextStep();
        },
        currentTitle() {
            return this.stepTitles[this.step - 1];
        },
        formatDate(value) {
            if (!value) return 'لم يتم تحديد موعد';
            return new Intl.DateTimeFormat('ar', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value));
        },
        minimumDateTime() {
            const date = new Date(Date.now() + 5 * 60 * 1000);
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
            return date.toISOString().slice(0, 16);
        },
        resetForm() {
            window.IhsanVoice?.stop();
            this.open = false;
            this.step = 1;
            this.service_type = '';
            this.title = '';
            this.location = this.profileCity;
            this.timing_type = 'scheduled';
            this.scheduled_at = '';
            this.description = '';
            this.errorMessage = '';
        }
    }"
    x-on:open-create-request-modal.window="open = true"
    x-on:keydown.escape.window="if (open) resetForm()"
    x-show="open"
    x-cloak
    class="request-wizard-overlay fixed inset-0 z-50 flex items-center justify-center p-3"
    role="dialog"
    aria-modal="true"
    aria-labelledby="request-wizard-title">

    <div class="absolute inset-0 bg-[#172011]/65 backdrop-blur-[2px]" @click="resetForm()"></div>

    <section class="request-wizard-card relative z-10 w-full">
        <header class="request-wizard-header">
            <div class="request-wizard-heading">
                <span>طلب مساعدة جديد</span>
                <h2 id="request-wizard-title" x-text="currentTitle()"></h2>
            </div>
            <button type="button" @click="resetForm()" class="request-wizard-close" aria-label="إغلاق">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="request-progress" aria-label="مراحل إنشاء الطلب">
                <div class="request-progress-line"><span :style="`width: ${((step - 1) / 4) * 100}%`"></span></div>
                <template x-for="(label, index) in stepLabels" :key="label">
                    <div class="request-progress-step" :class="{ active: step === index + 1, done: step > index + 1 }">
                        <span><i x-show="step > index + 1" class="fa-solid fa-check"></i><b x-show="step <= index + 1" x-text="index + 1"></b></span>
                        <small x-text="label"></small>
                    </div>
                </template>
            </div>
        </header>

        <form method="POST" action="{{ route('service-requests.store') }}" class="request-wizard-form">
            @csrf
            <input type="hidden" name="service_type" :value="service_type">
            <input type="hidden" name="title" :value="title">
            <input type="hidden" name="timing_type" :value="timing_type">
            <input type="hidden" name="gender_preference" value="any">
            <input type="hidden" name="pricing_type" value="volunteer">

            <div class="request-wizard-body">
                <div x-show="errorMessage" x-cloak class="request-wizard-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i><span x-text="errorMessage"></span>
                </div>

                <section x-show="step === 1" class="wizard-step-panel">
                    <p class="wizard-help">اضغط على الخدمة المناسبة. يمكنك تغيير اختيارك قبل الإرسال.</p>
                    <div class="service-choice-grid">
                        <template x-for="service in services" :key="service.id">
                            <button type="button" @click="selectService(service)" class="service-choice"
                                :class="{ selected: service_type === service.id }">
                                <span class="service-choice-icon"><i class="fa-solid" :class="service.icon"></i></span>
                                <span class="service-choice-copy"><strong x-text="service.title"></strong><small x-text="service.description"></small></span>
                                <span class="service-choice-check"><i class="fa-solid fa-check"></i></span>
                            </button>
                        </template>
                    </div>
                </section>

                <section x-show="step === 2" x-cloak class="wizard-step-panel compact-panel">
                    <div class="wizard-field-card">
                        <span class="wizard-field-icon"><i class="fa-solid fa-location-dot"></i></span>
                        <div class="wizard-field-content">
                            <label for="wizard-location">المنطقة أو العنوان</label>
                            <input id="wizard-location" type="text" name="location" x-model="location"
                                placeholder="مثال: رام الله، حي المصايف" autocomplete="street-address">
                            <button x-show="profileCity" type="button" @click="useProfileCity()" class="profile-city-button">
                                <i class="fa-solid fa-house"></i> استخدام مدينتي: <span x-text="profileCity"></span>
                            </button>
                        </div>
                    </div>
                    <p class="privacy-note"><i class="fa-solid fa-shield-heart"></i> لن يظهر عنوانك إلا لمقدم الخدمة الذي يتولى الطلب.</p>
                </section>

                <section x-show="step === 3" x-cloak class="wizard-step-panel compact-panel">
                    <div class="schedule-field schedule-field-required">
                        <span class="schedule-icon"><i class="fa-regular fa-calendar-check"></i></span>
                        <div class="schedule-copy">
                            <strong>حدد موعد الخدمة</strong>
                            <small>اختر اليوم والساعة المناسبة لك</small>
                        </div>
                        <label for="wizard-scheduled-at">اليوم والساعة <b>مطلوب</b></label>
                        <input id="wizard-scheduled-at" type="datetime-local" name="scheduled_at" x-model="scheduled_at"
                            required :min="minimumDateTime()" @input="errorMessage = ''">
                    </div>
                </section>

                <section x-show="step === 4" x-cloak class="wizard-step-panel compact-panel">
                    <div class="details-field">
                        <label for="wizard-description">تفاصيل تساعد مقدم الخدمة <span>اختياري</span></label>
                        <textarea id="wizard-description" name="description" x-model="description" rows="4"
                            placeholder="مثال: الرجاء الاتصال بي عند الوصول، أو اسم الدواء المطلوب..."></textarea>
                    </div>
                    <div class="detail-suggestions">
                        <button type="button" @click="description = 'الرجاء الاتصال بي عند الوصول'">اتصل بي عند الوصول</button>
                        <button type="button" @click="skipDetails()">ما عندي تفاصيل، متابعة</button>
                    </div>
                </section>

                <section x-show="step === 5" x-cloak class="wizard-step-panel compact-panel">
                    <div class="request-review">
                        <div class="review-row main"><span class="review-icon"><i class="fa-solid fa-hand-holding-heart"></i></span><div><small>الخدمة</small><strong x-text="title"></strong></div><button type="button" @click="step = 1">تعديل</button></div>
                        <div class="review-row"><span class="review-icon"><i class="fa-solid fa-location-dot"></i></span><div><small>المكان</small><strong x-text="location"></strong></div><button type="button" @click="step = 2">تعديل</button></div>
                        <div class="review-row"><span class="review-icon"><i class="fa-regular fa-clock"></i></span><div><small>الموعد</small><strong x-text="formatDate(scheduled_at)"></strong></div><button type="button" @click="step = 3">تعديل</button></div>
                        <div class="review-row"><span class="review-icon"><i class="fa-regular fa-note-sticky"></i></span><div><small>التفاصيل</small><strong x-text="description"></strong></div><button type="button" @click="step = 4">تعديل</button></div>
                    </div>
                    <p class="submit-note"><i class="fa-solid fa-circle-info"></i> لن يُرسل الطلب إلا بعد ضغط «تأكيد وإرسال».</p>
                </section>
            </div>

            <footer class="request-wizard-footer">
                <button x-show="step > 1" type="button" @click="prevStep()" class="wizard-back"><i class="fa-solid fa-arrow-right"></i> السابق</button>
                <button x-show="step < 5" type="button" @click="nextStep()" class="wizard-next">متابعة <i class="fa-solid fa-arrow-left"></i></button>
                <button x-show="step === 5" x-cloak type="submit" class="wizard-submit"><i class="fa-solid fa-paper-plane"></i> تأكيد وإرسال</button>
                <button x-show="step === 1" type="button" @click="resetForm()" class="wizard-cancel">إلغاء</button>
            </footer>
        </form>
    </section>
</div>

<style>
.request-wizard-card{max-width:720px;overflow:hidden;border:1px solid #dfe6d7;border-radius:22px;background:#fbfcfa;box-shadow:0 28px 85px rgba(21,32,15,.3);font-family:'Alexandria',sans-serif;color:#293321}
.request-wizard-header{position:relative;border-bottom:1px solid #e5eadf;background:#fff;padding:17px 20px 14px}.request-wizard-heading{padding-left:42px}.request-wizard-heading>span{color:#799264;font-size:10px;font-weight:800}.request-wizard-heading h2{margin-top:2px;color:#29401a;font-size:19px;font-weight:900}
.request-wizard-close{position:absolute;left:18px;top:16px;display:grid;width:34px;height:34px;place-items:center;border:1px solid #e2e7dd;border-radius:10px;background:#f7f9f5;color:#7f8978;transition:.2s}.request-wizard-close:hover{background:#fff0f0;color:#b42323}
.request-progress{position:relative;display:grid;grid-template-columns:repeat(5,1fr);margin-top:14px}.request-progress-line{position:absolute;right:10%;left:10%;top:13px;height:2px;background:#e4e9df}.request-progress-line span{display:block;height:100%;background:#6e8c53;transition:width .3s}
.request-progress-step{position:relative;z-index:1;display:flex;align-items:center;flex-direction:column;gap:4px;color:#a0a99a}.request-progress-step>span{display:grid;width:27px;height:27px;place-items:center;border:2px solid #e2e7dd;border-radius:50%;background:#fff;font-size:10px;font-weight:900}.request-progress-step small{font-size:8px;font-weight:800}.request-progress-step.active{color:#385225}.request-progress-step.active>span{border-color:#385225;background:#385225;color:#fff;box-shadow:0 0 0 4px #edf3e8}.request-progress-step.done{color:#688650}.request-progress-step.done>span{border-color:#85a66b;background:#85a66b;color:#fff}
.request-wizard-form{display:flex;flex-direction:column}.request-wizard-body{min-height:292px;max-height:56vh;overflow-y:auto;padding:17px 20px}.request-wizard-error{display:flex;align-items:center;gap:7px;margin-bottom:10px;border:1px solid #fecaca;border-radius:11px;background:#fff1f2;padding:8px 10px;color:#b42323;font-size:10px;font-weight:800}.wizard-step-panel{animation:wizardIn .2s ease}.wizard-help{margin-bottom:10px;color:#7d8776;font-size:10px;font-weight:700}.compact-panel{max-width:570px;margin-inline:auto}
.service-choice-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:9px}.service-choice{position:relative;display:flex;min-height:91px;align-items:center;flex-direction:column;justify-content:center;gap:5px;border:1px solid #dde4d7;border-radius:14px;background:#fff;padding:10px;text-align:center;transition:.2s}.service-choice:hover{transform:translateY(-2px);border-color:#91a97c;box-shadow:0 7px 16px rgba(45,66,30,.08)}.service-choice.selected{border-color:#466430;background:#f1f6ed;box-shadow:0 0 0 2px rgba(70,100,48,.12)}.service-choice-icon{display:grid;width:34px;height:34px;place-items:center;border-radius:10px;background:#eef3e9;color:#486333;font-size:13px}.service-choice.selected .service-choice-icon{background:#3b5727;color:#fff}.service-choice-copy strong,.service-choice-copy small{display:block}.service-choice-copy strong{color:#334724;font-size:11px;font-weight:900}.service-choice-copy small{margin-top:2px;color:#8c9586;font-size:8px;font-weight:600}.service-choice-check{position:absolute;top:7px;left:7px;display:none;width:17px;height:17px;place-items:center;border-radius:50%;background:#4b6b35;color:#fff;font-size:7px}.service-choice.selected .service-choice-check{display:grid}
.wizard-field-card{display:flex;gap:13px;border:1px solid #dfe5da;border-radius:16px;background:#fff;padding:15px}.wizard-field-icon{display:grid;width:42px;height:42px;flex:none;place-items:center;border-radius:12px;background:#edf3e8;color:#4d6a38}.wizard-field-content{min-width:0;flex:1}.wizard-field-content label,.schedule-field label,.details-field label{display:block;margin-bottom:6px;color:#37452d;font-size:10px;font-weight:900}.wizard-field-content input,.schedule-field input,.details-field textarea{width:100%;border:1px solid #d9e0d3;border-radius:11px;background:#fafbf9;padding:10px 11px;color:#27321f;font-size:11px}.profile-city-button{display:inline-flex;align-items:center;gap:5px;margin-top:8px;border-radius:9px;background:#edf3e8;padding:6px 9px;color:#4d6939;font-size:9px;font-weight:800}.privacy-note,.submit-note{display:flex;align-items:center;gap:7px;margin-top:10px;border-radius:10px;background:#f1f5ed;padding:8px 10px;color:#64735a;font-size:9px;font-weight:700}
.timing-choice-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.timing-choice{display:flex;min-height:112px;align-items:center;flex-direction:column;justify-content:center;border:1px solid #dfe5da;border-radius:16px;background:#fff;padding:12px;transition:.2s}.timing-choice>span{display:grid;width:39px;height:39px;place-items:center;border-radius:12px;background:#eef3e9;color:#4c6837}.timing-choice strong{margin-top:7px;color:#354a26;font-size:12px;font-weight:900}.timing-choice small{margin-top:2px;color:#91998c;font-size:9px}.timing-choice.selected{border-color:#486732;background:#f1f6ed;box-shadow:0 0 0 2px rgba(72,103,50,.12)}.timing-choice.selected>span{background:#3c5929;color:#fff}.schedule-field{margin-top:11px;border:1px solid #e0e6da;border-radius:13px;background:#fff;padding:11px}.schedule-field-required{display:grid;grid-template-columns:44px minmax(0,1fr);align-items:center;gap:10px;margin-top:0;padding:17px}.schedule-icon{display:grid;width:44px;height:44px;grid-row:1;place-items:center;border-radius:13px;background:#3c5929;color:#fff;font-size:16px}.schedule-copy{grid-column:2}.schedule-copy strong,.schedule-copy small{display:block}.schedule-copy strong{color:#354a26;font-size:13px;font-weight:900}.schedule-copy small{margin-top:2px;color:#7d8875;font-size:9px}.schedule-field-required label,.schedule-field-required input{grid-column:1/-1}.schedule-field-required label{margin-top:7px}.schedule-field-required label b{margin-right:4px;border-radius:999px;background:#f0e6d4;padding:2px 7px;color:#8a642b;font-size:8px}
.details-field{border:1px solid #dfe5da;border-radius:15px;background:#fff;padding:13px}.details-field label span{margin-right:4px;border-radius:999px;background:#edf2e8;padding:2px 6px;color:#718263;font-size:8px}.details-field textarea{min-height:105px;resize:vertical}.detail-suggestions{display:flex;flex-wrap:wrap;gap:7px;margin-top:9px}.detail-suggestions button{border:1px solid #dce4d6;border-radius:999px;background:#f5f8f2;padding:7px 10px;color:#536d40;font-size:9px;font-weight:800}
.request-review{display:grid;gap:7px}.review-row{display:grid;grid-template-columns:34px minmax(0,1fr) auto;align-items:center;gap:9px;border:1px solid #e2e7de;border-radius:12px;background:#fff;padding:9px 10px}.review-row.main{border-color:#cddbc2;background:#f4f8f1}.review-icon{display:grid;width:32px;height:32px;place-items:center;border-radius:9px;background:#eef3e9;color:#4b6736;font-size:11px}.review-row small,.review-row strong{display:block}.review-row small{color:#90998a;font-size:8px;font-weight:800}.review-row strong{overflow:hidden;margin-top:1px;color:#35442c;font-size:10px;font-weight:900;text-overflow:ellipsis;white-space:nowrap}.review-row button{border-radius:8px;background:#edf3e8;padding:5px 7px;color:#526d3e;font-size:8px;font-weight:800}
.request-wizard-footer{display:flex;align-items:center;gap:8px;border-top:1px solid #e4e9df;background:#fff;padding:11px 20px}.request-wizard-footer button{display:inline-flex;min-height:39px;align-items:center;justify-content:center;gap:7px;border-radius:11px;padding:8px 15px;font-size:10px;font-weight:900;transition:.2s}.wizard-next,.wizard-submit{margin-right:auto;min-width:145px;background:#314b20;color:#fff;box-shadow:0 6px 14px rgba(49,75,32,.16)}.wizard-next:hover,.wizard-submit:hover{background:#42632b}.wizard-back{border:1px solid #dce3d6;background:#fff;color:#53644a}.wizard-cancel{color:#8d9588}
@keyframes wizardIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:none}}
@media(max-width:640px){.request-wizard-overlay{padding:7px}.request-wizard-card{max-height:96vh;border-radius:17px}.request-wizard-header{padding:14px 13px 11px}.request-wizard-heading{padding-left:38px}.request-wizard-heading h2{font-size:16px}.request-progress-step small{display:none}.request-wizard-body{min-height:330px;max-height:62vh;padding:13px}.service-choice-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:7px}.service-choice{min-height:86px}.timing-choice-grid{grid-template-columns:1fr}.timing-choice{min-height:90px}.request-wizard-footer{padding:9px 12px}.request-wizard-footer button{min-height:42px}.wizard-next,.wizard-submit{min-width:130px}}
</style>