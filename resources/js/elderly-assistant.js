import { RunnableLambda } from '@langchain/core/runnables';
import { startArabicSpeechRecognition } from './speech-recognition';
import { appointmentVoiceKeys, parseArabicAppointment } from './appointment-parser';
import { parseAppointmentWithAI } from './appointment-ai';
import { assistantReplies } from './assistant-replies';

const services = [
    { id: 'grocery', title: 'شراء أغراض', icon: '🛒', keywords: ['اغراض', 'أغراض', 'تسوق', 'شراء', 'سوبرماركت'] },
    { id: 'medicine', title: 'شراء دواء', icon: '💊', keywords: ['دواء', 'ادوية', 'أدوية', 'صيدلية', 'علاج'] },
    { id: 'medical_escort', title: 'مرافقة طبية', icon: '🏥', keywords: ['مستشفى', 'طبيب', 'دكتور', 'عيادة', 'مرافقة'] },
    { id: 'social_visit', title: 'زيارة اجتماعية', icon: '👥', keywords: ['زيارة', 'مؤانسة', 'اجتماعية', 'صحبة'] },
    { id: 'home_help', title: 'مساعدة منزلية', icon: '🏠', keywords: ['منزل', 'بيت', 'تنظيف', 'ترتيب'] },
    { id: 'support_request', title: 'خدمة أخرى', icon: '🤲', keywords: ['اخرى', 'أخرى', 'غير ذلك', 'دعم'] },
];

const normalizeMessage = RunnableLambda.from((input) => ({
    ...input,
    text: String(input.text || '').trim(),
    normalized: String(input.text || '').trim().toLowerCase(),
}));

const detectIntent = RunnableLambda.from((input) => {
    const includesAny = (words) => words.some((word) => input.normalized.includes(word));
    let intent = 'unknown';
    if (includesAny(['اشعار', 'إشعار', 'تنبيه'])) intent = 'notifications';
    else if (includesAny(['طلباتي', 'متابعة', 'حالة الطلب', 'وين طلبي'])) intent = 'tracking';
    else if (includesAny(['خدمة', 'مساعدة', 'بدي', 'احتاج', 'أحتاج', ...services.flatMap((service) => service.keywords)])) intent = 'service';

    const service = services.find((item) => includesAny(item.keywords));
    return { ...input, intent, service: service || null };
});

const conversationChain = normalizeMessage.pipe(detectIntent);

const prepareDraft = RunnableLambda.from((draft) => {
    const selectedService = services.find((service) => service.id === draft.service_type);
    const title = selectedService?.title || draft.title || 'طلب مساعدة';
    return {
        ...draft,
        title,
        description: draft.description?.trim() || `أحتاج إلى خدمة ${title}`,
        gender_preference: 'any',
        pricing_type: 'volunteer',
    };
});

const buildSummary = RunnableLambda.from((draft) => {
    const timing = new Intl.DateTimeFormat('ar', { dateStyle: 'long', timeStyle: 'short' }).format(new Date(draft.scheduled_at));

    return {
        draft,
        timing,
        speech: `الخدمة: ${draft.title}. المكان: ${draft.location}. الموعد: ${timing}. التفاصيل: ${draft.description}.`,
        display: `الخدمة: ${draft.title}\nالمكان: ${draft.location}\nالموعد: ${timing}\nالتفاصيل: ${draft.description}`,
    };
});

const summaryChain = prepareDraft.pipe(buildSummary);

window.elderlyAssistant = (profileCity = '') => ({
    step: 0,
    chatInput: '',
    editing: false,
    submitting: false,
    listening: false,
    errorMessage: '',
    appointmentTranscript: '',
    profileCity,
    prepared: null,
    messages: [],
    services,
    draft: {
        service_type: '',
        title: '',
        location: '',
        timing_type: 'scheduled',
        scheduled_at: '',
        description: '',
    },

    init() {
        try {
            const saved = JSON.parse(localStorage.getItem('ihsan_elderly_request_draft'));
            if (saved && typeof saved === 'object') this.draft = { ...this.draft, ...saved };
        } catch (_) { }

        this.addBot('أهلًا فيك! أنا مساعد أنيس الذكي. احكيلي شو بتحتاج، أو اختار واحد من الاقتراحات تحت.', 'welcome');
    },

    fixedVoice(key, fallbackText = '') {
        return window.IhsanVoice?.playFixed(key, fallbackText);
    },

    addMessage(role, text, kind = 'text', voiceKey = null) {
        this.messages.push({ id: Date.now() + Math.random(), role, text, kind, voiceKey });
        this.$nextTick(() => {
            const box = this.$refs.messages;
            if (box) box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
        });
    },

    addBot(text, voiceKey = null) {
        this.addMessage('assistant', text, 'text', voiceKey);
        if (voiceKey) window.IhsanVoice?.playFixed(voiceKey, text);
    },

    addUser(text) {
        this.addMessage('user', text);
    },

    saveDraft() {
        try { localStorage.setItem('ihsan_elderly_request_draft', JSON.stringify(this.draft)); } catch (_) { }
    },

    resetChat() {
        window.IhsanVoice?.stop();
        this.step = 0;
        this.editing = false;
        this.errorMessage = '';
        this.chatInput = '';
        this.messages = [];
        this.addBot('بدأنا محادثة جديدة. احكيلي كيف بقدر أساعدك؟', 'welcome');
    },

    cancelDraft() {
        try { localStorage.removeItem('ihsan_elderly_request_draft'); } catch (_) { }
        this.draft = {
            service_type: '',
            title: '',
            location: '',
            timing_type: 'scheduled',
            scheduled_at: '',
            description: '',
        };
        this.prepared = null;
        this.step = 0;
        this.editing = false;
        this.errorMessage = '';
        this.chatInput = '';
        this.messages = [];
        this.addBot('تم إلغاء الطلب ومسح المعلومات. كيف بقدر أساعدك من جديد؟', 'cancel');
    },

    async startRequestWithVoice() {
        await window.IhsanVoice?.playFixed('button_request', 'طلب خدمة');
        this.startRequest();
    },

    async navigateWithVoice(voiceKey, label, url) {
        await window.IhsanVoice?.playFixed(voiceKey, label);
        window.location.assign(url);
    },

    startRequest() {
        this.addUser('بدي أطلب خدمة');
        this.step = 1;
        this.addBot('أكيد، شو نوع الخدمة اللي بدك إياها؟', 'service');
    },

    async sendText() {
        const text = this.chatInput.trim();
        if (!text || this.submitting) return;
        this.chatInput = '';
        this.errorMessage = '';

        if (this.step === 0) {
            this.addUser(text);
            const result = await conversationChain.invoke({ text });

            if (result.intent === 'tracking') {
                this.addBot('تمام، رح أفتحلك صفحة متابعة طلباتك.');
                setTimeout(() => window.location.assign('/requests'), 500);
                return;
            }
            if (result.intent === 'notifications') {
                this.addBot('تمام، رح أفتحلك الإشعارات.');
                setTimeout(() => window.location.assign('/notifications'), 500);
                return;
            }
            if (result.intent === 'service') {
                if (result.service) {
                    this.step = 1;
                    this.chooseService(result.service, false);
                } else {
                    this.step = 1;
                    this.addBot('تمام، شو نوع الخدمة اللي بدك إياها؟', 'service');
                }
                return;
            }

            this.addBot(assistantReplies.unknown.text, assistantReplies.unknown.voiceKey);
            return;
        }

        if (this.step === 1) {
            const result = await conversationChain.invoke({ text });
            this.addUser(text);
            if (result.service) this.chooseService(result.service, false);
            else this.addBot('ما قدرت أحدد نوع الخدمة. اختار من الأزرار الموجودة تحت.');
            return;
        }

        if (this.step === 2) {
            this.draft.location = text;
            this.saveDraft();
            this.addUser(text);
            if (this.editing) {
                await this.finishEdit();
                return;
            }
            this.draft.timing_type = 'scheduled';
            this.step = 3;
            this.saveDraft();
            this.addBot('ممتاز. حدد اليوم والساعة المناسبة للخدمة.', 'timing');
            return;
        }

        if (this.step === 4) {
            this.draft.description = text;
            this.saveDraft();
            this.addUser(text);
            await this.showSummary();
        }
    },

    async chooseService(service, showUser = true) {
        this.draft.service_type = service.id;
        this.draft.title = service.title;
        this.saveDraft();
        if (showUser) this.addUser(service.icon + ' ' + service.title);

        await window.IhsanVoice?.playFixed('service_' + service.id, service.title);

        if (this.editing) {
            await this.finishEdit();
            return;
        }

        this.step = 2;
        this.addBot('وين بدك تنفيذ الخدمة؟ اكتب العنوان أو استخدم عنوان مدينتك.', 'location');
    },

    useProfileCity() {
        this.chatInput = this.profileCity;
        this.sendText();
    },

    async playAppointmentDate(value = this.draft.scheduled_at) {
        if (!value) return;
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return;

        for (const voiceKey of appointmentVoiceKeys(date)) {
            await window.IhsanVoice?.playFixed(voiceKey, '');
        }
    },

    async startAppointmentListening() {
        const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.errorMessage = '';
        this.appointmentTranscript = '';

        if (!Recognition) {
            this.errorMessage = 'اختيار الموعد بالصوت يحتاج Chrome أو Edge.';
            window.IhsanVoice?.playFixed('appointment_error', '');
            return;
        }

        this.listening = true;
        await window.IhsanVoice?.playFixed('appointment_prompt', '');
        startArabicSpeechRecognition({
            Recognition,
            stopAudio: () => window.IhsanVoice?.stop(),
            onListeningChange: (value) => { this.listening = value; },
            onTranscript: async (text) => {
                this.appointmentTranscript = text;
                this.addUser('🎤 ' + text);
                this.understandingAppointment = true;
                const parsed = await parseAppointmentWithAI(text, { fallback: parseArabicAppointment });
                this.understandingAppointment = false;

                if (!parsed.ok) {
                    this.errorMessage = parsed.error;
                    this.addBot(parsed.error);
                    await window.IhsanVoice?.playFixed('appointment_error', '');
                    return;
                }

                this.draft.timing_type = 'scheduled';
                this.draft.scheduled_at = parsed.scheduledAt;
                this.saveDraft();
                await this.playAppointmentDate(parsed.date);
            },
            onError: async (message) => {
                this.errorMessage = message;
                await window.IhsanVoice?.playFixed('appointment_error', '');
            },
        });
    },

    async confirmScheduledTime() {
        this.draft.timing_type = 'scheduled';
        if (!this.draft.scheduled_at || new Date(this.draft.scheduled_at) <= new Date()) {
            this.errorMessage = 'اختار موعدًا صحيحًا في المستقبل.';
            return;
        }

        this.errorMessage = '';
        const label = new Intl.DateTimeFormat('ar', { dateStyle: 'long', timeStyle: 'short' }).format(new Date(this.draft.scheduled_at));
        this.addUser(label);
        this.saveDraft();
        await window.IhsanVoice?.playFixed('appointment_confirmed', '');
        if (this.editing) return this.finishEdit();
        this.askDetails();
    },

    askDetails() {
        this.step = 4;
        this.addBot('في تفاصيل إضافية بتحب تحكيلنا إياها؟ تقدر كمان تتخطى السؤال.', 'details');
    },

    skipDetails() {
        this.addUser('ما في تفاصيل إضافية');
        this.showSummary();
    },

    async showSummary() {
        if (!this.draft.scheduled_at || new Date(this.draft.scheduled_at) <= new Date()) {
            this.errorMessage = 'لازم تحدد موعدًا صحيحًا في المستقبل.';
            this.step = 3;
            return;
        }
        this.draft.timing_type = 'scheduled';
        this.prepared = await summaryChain.invoke({ ...this.draft });
        this.draft = { ...this.prepared.draft };
        this.saveDraft();
        this.step = 5;
        this.editing = false;
        this.addMessage('assistant', this.prepared.display, 'summary');
        this.addBot('راجع المعلومات، وإذا صحيحة اضغط تأكيد وإرسال.', 'review');
    },

    readSummary() {
        if (this.prepared) window.IhsanVoice?.speakDynamic(this.prepared.speech);
    },

    openEditMenu() {
        this.step = 6;
        this.addUser('بدي أعدّل الطلب');
        this.addBot('أكيد، شو بدك تعدّل؟', 'edit');
    },

    editStep(target) {
        this.editing = true;
        this.step = target;
        const prompts = {
            1: ['شو نوع الخدمة الجديد؟', 'service'],
            2: ['اكتب المكان الجديد.', 'location'],
            3: ['اختار الموعد الجديد.', 'timing'],
            4: ['اكتب التفاصيل الجديدة.', 'details'],
        };
        this.addBot(...prompts[target]);
    },

    async finishEdit() {
        this.editing = false;
        await this.showSummary();
    },

    minimumDateTime() {
        const date = new Date(Date.now() + 5 * 60 * 1000);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        return date.toISOString().slice(0, 16);
    },

    speakMessage(text, voiceKey = null) {
        if (voiceKey) return window.IhsanVoice?.playFixed(voiceKey, text);
        return window.IhsanVoice?.speakDynamic(text);
    },

    speakService(service) {
        window.IhsanVoice?.playFixed(`service_${service.id}`, service.title);
    },

    startListening() {
        const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.errorMessage = '';

        if (!Recognition) {
            this.errorMessage = 'الميكروفون الصوتي غير مدعوم هنا. استخدم Chrome أو Edge.';
            return;
        }

        startArabicSpeechRecognition({
            Recognition,
            stopAudio: () => window.IhsanVoice?.stop(),
            onListeningChange: (value) => { this.listening = value; },
            onTranscript: (text) => {
                this.chatInput = text;
                this.$nextTick(() => this.sendText());
            },
            onError: (message) => { this.errorMessage = message; },
        });
    },

    async submitRequest(event) {
        if (this.submitting) return;
        if (!this.draft.scheduled_at || new Date(this.draft.scheduled_at) <= new Date()) {
            this.errorMessage = 'لازم تحدد موعدًا صحيحًا في المستقبل.';
            this.step = 3;
            return;
        }
        this.draft.timing_type = 'scheduled';
        this.submitting = true;
        this.errorMessage = '';

        try {
            this.prepared = await summaryChain.invoke({ ...this.draft });
            this.draft = { ...this.prepared.draft };
            await this.$nextTick();

            const response = await fetch(event.target.action, {
                method: 'POST',
                body: new FormData(event.target),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                const firstError = Object.values(payload.errors || {})[0];
                throw new Error(Array.isArray(firstError) ? firstError[0] : 'تعذر إرسال الطلب. حاول مرة أخرى.');
            }

            try { localStorage.removeItem('ihsan_elderly_request_draft'); } catch (_) { }
            this.addBot('تم إرسال طلبك بنجاح. رح أحولك الآن لصفحة المتابعة.');
            await window.IhsanVoice?.playFixed('success', 'تم إرسال طلبك بنجاح');
            window.location.assign(response.url);
        } catch (error) {
            this.errorMessage = error.message || 'تعذر إرسال الطلب. حاول مرة أخرى.';
            this.addBot(this.errorMessage);
            this.submitting = false;
        }
    },
});
