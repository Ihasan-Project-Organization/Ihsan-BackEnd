@props(['profileCity' => ''])

<section id="elderly-assistant" class="assistant-shell" x-data="elderlyAssistant(@js($profileCity ?? ''))" aria-labelledby="assistant-title" x-on:assistant-start-request.window="if (step === 0) startRequest()">
    <header class="assistant-topbar">
        <div class="flex min-w-0 items-center gap-3">
            <span class="assistant-avatar" aria-hidden="true">
                <i class="fa-solid fa-robot"></i>
                <span class="assistant-avatar-pulse"></span>
            </span>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 id="assistant-title" class="text-lg font-black text-white sm:text-xl">مساعد أنيس الذكي</h1>
                    <span class="assistant-online"><span></span> متصل الآن</span>
                </div>
                <p class="mt-0.5 text-[11px] font-bold text-[#dce7d2]">محادثة صوتية سهلة وآمنة</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="fixedVoice('welcome', 'أهلًا فيك! أنا مساعد أنيس الذكي. احكيلي شو بتحتاج، أو اختار واحد من الاقتراحات تحت.')"
                class="assistant-header-button" data-voice-key="welcome" aria-label="سماع الترحيب">
                <i class="fa-solid fa-volume-high"></i><span class="hidden sm:inline">سماع الترحيب</span>
            </button>
            <button x-show="step > 0" x-cloak type="button" @click="cancelDraft()"
                class="assistant-header-button cancel" aria-label="إلغاء الطلب الحالي">
                <i class="fa-solid fa-xmark"></i><span class="hidden sm:inline">إلغاء الطلب</span>
            </button>
            <button type="button" @click="resetChat()" class="assistant-header-button" aria-label="ابدأ محادثة جديدة">
                <i class="fa-solid fa-rotate-right"></i><span class="hidden sm:inline">محادثة جديدة</span>
            </button>
        </div>
    </header>

    <form method="POST" action="{{ route('service-requests.store') }}" @submit.prevent="submitRequest($event)" class="assistant-chat">
        @csrf
        <input type="hidden" name="service_type" :value="draft.service_type">
        <input type="hidden" name="title" :value="draft.title">
        <input type="hidden" name="timing_type" :value="draft.timing_type">
        <input type="hidden" name="scheduled_at" :value="draft.scheduled_at">
        <input type="hidden" name="description" :value="draft.description">
        <input type="hidden" name="location" :value="draft.location">
        <input type="hidden" name="gender_preference" value="any">
        <input type="hidden" name="pricing_type" value="volunteer">

        <div x-show="step > 0 && step < 6" x-cloak class="assistant-progress-wrap">
            <div class="flex items-center justify-between gap-3 text-xs font-black text-[#52643a]">
                <span>إنشاء طلب جديد</span>
                <span x-text="step < 5 ? `الخطوة ${step} من 4` : 'المراجعة النهائية'"></span>
            </div>
            <div class="assistant-progress"><span :style="`width: ${Math.min(step, 4) * 25}%`"></span></div>
        </div>

        <div x-ref="messages" class="assistant-messages" role="log" aria-live="polite" aria-label="محادثة المساعد">
            <template x-for="message in messages" :key="message.id">
                <div class="assistant-message-row" :class="message.role">
                    <span x-show="message.role === 'assistant'" class="assistant-mini-avatar" aria-hidden="true"><i class="fa-solid fa-robot"></i></span>
                    <div class="assistant-bubble" :class="{ 'summary': message.kind === 'summary' }">
                        <p x-text="message.text"></p>
                        <button x-show="message.role === 'assistant'" type="button" @click="speakMessage(message.text, message.voiceKey)"
                            class="assistant-read-message" aria-label="اقرأ هذه الرسالة">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                </div>
            </template>
            <div x-show="submitting" class="assistant-message-row assistant">
                <span class="assistant-mini-avatar"><i class="fa-solid fa-robot"></i></span>
                <div class="assistant-bubble assistant-typing"><span></span><span></span><span></span></div>
            </div>
        </div>

        <div class="assistant-replies">
            <div x-show="step === 0" class="grid gap-3 sm:grid-cols-3">
                <button type="button" @click="startRequestWithVoice()" class="assistant-reply-card service"
                    data-voice-key="button_request" data-voice-text="طلب خدمة">
                    <span class="icon"><i class="fa-solid fa-hand-holding-heart"></i></span>
                    <span><strong>طلب خدمة</strong><small>خلينا نعمل الطلب سوا</small></span>
                </button>
                <a href="{{ route('service-requests.index') }}"
                    @click.prevent="navigateWithVoice('button_tracking', 'متابعة طلباتي', $el.href)"
                    class="assistant-reply-card tracking" data-voice-key="button_tracking" data-voice-text="متابعة طلباتي">
                    <span class="icon"><i class="fa-solid fa-list-check"></i></span>
                    <span><strong>متابعة طلباتي</strong><small>شوف حالة طلباتك</small></span>
                </a>
                <a href="{{ route('notifications.index') }}"
                    @click.prevent="navigateWithVoice('button_notifications', 'الإشعارات', $el.href)"
                    class="assistant-reply-card notifications" data-voice-key="button_notifications" data-voice-text="الإشعارات">
                    <span class="icon"><i class="fa-solid fa-bell"></i></span>
                    <span><strong>الإشعارات</strong><small>اقرأ آخر التنبيهات</small></span>
                </a>
            </div>

            <div x-show="step === 1" x-cloak class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
                <template x-for="service in services" :key="service.id">
                    <div class="assistant-service-option" :class="{ selected: draft.service_type === service.id }">
                        <button type="button" @click="chooseService(service)" class="assistant-service-chip">
                            <span x-text="service.icon" aria-hidden="true"></span>
                            <strong x-text="service.title"></strong>
                        </button>
                        <button type="button" @click.stop="speakService(service)" class="assistant-service-sound"
                            :aria-label="`استمع إلى اسم خدمة ${service.title}`" title="استمع للخدمة">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                </template>
            </div>

            <div x-show="step === 2 && profileCity" x-cloak>
                <button type="button" @click="useProfileCity()" class="assistant-suggestion">
                    <i class="fa-solid fa-location-dot"></i>
                    استخدام مدينتي: <span x-text="profileCity"></span>
                </button>
            </div>

            <div x-show="step === 3" x-cloak class="space-y-3">
                <div class="assistant-date-box required-date">
                    <div class="assistant-date-heading">
                        <span><i class="fa-regular fa-calendar-check"></i></span>
                        <div><strong>حدد موعد الخدمة</strong><small>اختيار اليوم والساعة مطلوب لإكمال الطلب</small></div>
                    </div>
                    <div class="assistant-appointment-voice">
                        <button type="button" @click="startAppointmentListening()" :disabled="listening || understandingAppointment"
                            class="assistant-appointment-mic" :class="{ active: listening }"
                            :aria-label="listening ? 'جاري الاستماع للموعد' : 'احكي موعد الخدمة'">
                            <span><i class="fa-solid" :class="listening ? 'fa-wave-square' : 'fa-microphone'"></i></span>
                            <span><strong x-text="listening ? 'أنا سامعك... احكي الآن' : 'احكي الموعد بصوتك'"></strong><small>مثال: بكرا الساعة ثلاثة العصر</small></span>
                        </button>
                        <button x-show="draft.scheduled_at" x-cloak type="button" @click="playAppointmentDate()" class="assistant-replay-date">
                            <i class="fa-solid fa-volume-high"></i> اسمع الموعد
                        </button>
                        <p x-show="appointmentTranscript" x-cloak><i class="fa-solid fa-check"></i> سمعت: <b x-text="appointmentTranscript"></b></p>
                        <p x-show="understandingAppointment" x-cloak class="assistant-ai-thinking"><i class="fa-solid fa-wand-magic-sparkles"></i> جاري فهم اليوم والساعة...</p>
                    </div>
                    <div class="assistant-date-divider"><span>أو اختار من التقويم</span></div>
                    <label for="assistant-time">اليوم والساعة <b>مطلوب</b></label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input id="assistant-time" type="datetime-local" x-model="draft.scheduled_at" :min="minimumDateTime()" required @input="errorMessage = ''">
                        <button type="button" @click="confirmScheduledTime()" class="assistant-send compact">تأكيد الموعد</button>
                    </div>
                </div>
            </div>

            <div x-show="step === 4" x-cloak class="flex flex-wrap gap-2">
                <button type="button" @click="chatInput = 'الرجاء الاتصال بي عند الوصول'; sendText()" class="assistant-suggestion">اتصل بي عند الوصول</button>
                <button type="button" @click="skipDetails()" class="assistant-suggestion muted">تخطي، ما في تفاصيل</button>
            </div>

            <div x-show="step === 5" x-cloak class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <button type="button" @click="readSummary()" class="assistant-action listen"><i class="fa-solid fa-volume-high"></i> قراءة الملخص</button>
                <button type="button" @click="openEditMenu()" class="assistant-action edit"><i class="fa-solid fa-pen"></i> تعديل</button>
                <button type="button" @click="cancelDraft()" class="assistant-action cancel"><i class="fa-solid fa-xmark"></i> إلغاء الطلب</button>
                <button type="submit" :disabled="submitting" class="assistant-action confirm"><i class="fa-solid fa-paper-plane"></i> تأكيد وإرسال</button>
            </div>

            <div x-show="step === 6" x-cloak class="grid grid-cols-2 gap-3">
                <button type="button" @click="editStep(1)" class="assistant-big-reply">نوع الخدمة</button>
                <button type="button" @click="editStep(2)" class="assistant-big-reply">المكان</button>
                <button type="button" @click="editStep(3)" class="assistant-big-reply">الموعد</button>
                <button type="button" @click="editStep(4)" class="assistant-big-reply">التفاصيل</button>
            </div>

            <p x-show="errorMessage" x-text="errorMessage" class="assistant-error" role="alert"></p>
        </div>

        <div x-show="[0, 2, 4].includes(step)" class="assistant-composer">
            <button type="button" @click="startListening()" class="assistant-mic" :class="{ active: listening }"
                :disabled="listening" :title="listening ? 'جاري الاستماع... احكي الآن' : 'استخدم الميكروفون'"
                :aria-label="listening ? 'جاري الاستماع، احكي الآن' : 'استخدم الميكروفون'">
                <i class="fa-solid" :class="listening ? 'fa-wave-square' : 'fa-microphone'"></i>
            </button>
            <textarea x-model="chatInput" @keydown.enter.prevent="sendText()" rows="1"
                :placeholder="listening ? 'جاري الاستماع... احكي الآن' : (step === 0 ? 'اكتب مثلاً: بدي حدا يجيبلي دواء...' : (step === 2 ? 'اكتب عنوانك هنا...' : 'اكتب التفاصيل، أو اضغط تخطي...'))"
                aria-label="اكتب رسالتك"></textarea>
            <button type="button" @click="sendText()" :disabled="!chatInput.trim()" class="assistant-send" aria-label="إرسال الرسالة">
                <i class="fa-solid fa-paper-plane"></i><span class="hidden sm:inline">إرسال</span>
            </button>
        </div>

        <footer class="assistant-footer">
            <span><i class="fa-solid fa-volume-high"></i> جميع الأزرار ناطقة: مرّر عليها لسماع وظيفتها</span>
            <span class="assistant-footer-divider"></span>
            <span><i class="fa-solid fa-shield-heart"></i> لن يُرسل أي طلب قبل ضغط «تأكيد وإرسال»</span>
        </footer>
    </form>
</section>

<style>
#elderly-assistant.assistant-shell{position:relative;isolation:isolate;width:min(100%,56rem);min-height:62vh;margin-inline:auto;overflow:hidden;border:1px solid #d6dfcc;border-radius:1.5rem;background:#f4f7f0;box-shadow:0 18px 48px rgba(42,61,24,.14)}
#elderly-assistant .assistant-topbar{position:relative;display:flex;align-items:center;justify-content:space-between;gap:.75rem;overflow:hidden;padding:.8rem 1rem;background:linear-gradient(125deg,#233414,#36501f 58%,#58783a);box-shadow:0 8px 28px rgba(35,52,20,.2)}
#elderly-assistant .assistant-topbar:after{content:"";position:absolute;left:-5rem;top:-8rem;width:23rem;height:23rem;border:3.5rem solid rgba(255,255,255,.04);border-radius:50%;pointer-events:none}
#elderly-assistant .assistant-avatar{position:relative;display:flex;width:3.5rem;height:3.5rem;flex:none;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.3);border-radius:1rem;background:linear-gradient(145deg,rgba(255,255,255,.23),rgba(255,255,255,.08));box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 8px 18px rgba(0,0,0,.16);font-size:1.45rem;color:white}
#elderly-assistant .assistant-avatar-pulse{position:absolute;left:-.1rem;bottom:-.1rem;width:1.05rem;height:1.05rem;border:.2rem solid #30471d;border-radius:50%;background:#6ee7b7;box-shadow:0 0 0 .25rem rgba(110,231,183,.15)}
#elderly-assistant .assistant-online{display:inline-flex;align-items:center;gap:.35rem;border-radius:2rem;background:rgba(255,255,255,.12);padding:.3rem .6rem;font-size:.65rem;font-weight:800;color:#e5f1dd}
#elderly-assistant .assistant-online span{width:.45rem;height:.45rem;border-radius:50%;background:#6ee7b7}
#elderly-assistant .assistant-header-button{position:relative;z-index:1;display:inline-flex;min-height:2.35rem;align-items:center;gap:.4rem;border:1px solid rgba(255,255,255,.18);border-radius:.75rem;background:rgba(255,255,255,.1);padding:.45rem .65rem;font-size:.72rem;font-weight:900;color:white;transition:.2s}
#elderly-assistant .assistant-header-button:hover{background:white;color:#31421e}#elderly-assistant .assistant-header-button.cancel{border-color:rgba(254,202,202,.4);background:rgba(190,24,93,.18)}#elderly-assistant .assistant-header-button.cancel:hover{background:#fff1f2;color:#be123c}
#elderly-assistant .assistant-chat{display:flex;min-height:calc(62vh - 5rem);flex-direction:column}
#elderly-assistant .assistant-progress-wrap{border-bottom:1px solid #e2e8dc;background:rgba(255,255,255,.75);padding:.8rem 1.5rem}
#elderly-assistant .assistant-progress{height:.35rem;margin-top:.55rem;overflow:hidden;border-radius:1rem;background:#e1e7dc}
#elderly-assistant .assistant-progress span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#718256,#a5b98b);transition:width .35s}
#elderly-assistant .assistant-messages{height:245px;overflow-y:auto;padding:1rem 1.15rem;background:radial-gradient(circle at 1px 1px,rgba(113,130,86,.11) 1px,transparent 0);background-size:22px 22px;scrollbar-color:#a9b99a transparent}
#elderly-assistant .assistant-message-row{display:flex;max-width:86%;align-items:flex-end;gap:.5rem;margin-bottom:.7rem}
#elderly-assistant .assistant-message-row.assistant{margin-left:auto}
#elderly-assistant .assistant-message-row.user{margin-right:auto;flex-direction:row-reverse}
#elderly-assistant .assistant-mini-avatar{display:flex;width:2.35rem;height:2.35rem;flex:none;align-items:center;justify-content:center;border-radius:.85rem;background:linear-gradient(145deg,#31421e,#668448);color:#fff;font-size:.9rem;box-shadow:0 5px 12px rgba(49,66,30,.2)}
#elderly-assistant .assistant-bubble{position:relative;border:1px solid #d9e3cf;border-radius:1.1rem .35rem 1.1rem 1.1rem;background:white;padding:.65rem 2.55rem .65rem .85rem;color:#25301d;box-shadow:0 5px 14px rgba(49,66,30,.07)}
#elderly-assistant .assistant-bubble p{white-space:pre-line;font-size:.88rem;font-weight:700;line-height:1.65}
#elderly-assistant .user .assistant-bubble{border-color:#31421e;border-radius:.35rem 1.35rem 1.35rem 1.35rem;background:linear-gradient(135deg,#31421e,#4d6833);padding:.9rem 1.1rem;color:white;box-shadow:0 8px 20px rgba(49,66,30,.2)}
#elderly-assistant .assistant-bubble.summary{border:2px solid #9daf88;background:linear-gradient(145deg,#f7faf4,#eef4e8)}
#elderly-assistant .assistant-read-message{position:absolute;left:.65rem;top:.65rem;display:flex;width:1.9rem;height:1.9rem;align-items:center;justify-content:center;border-radius:.65rem;background:#edf3e7;color:#52643a;font-size:.72rem}
#elderly-assistant .assistant-typing{display:flex;gap:.3rem;padding:1rem 1.2rem}
#elderly-assistant .assistant-typing span{width:.45rem;height:.45rem;border-radius:50%;background:#718256;animation:assistantTyping 1s infinite alternate}
#elderly-assistant .assistant-typing span:nth-child(2){animation-delay:.2s}#elderly-assistant .assistant-typing span:nth-child(3){animation-delay:.4s}
@keyframes assistantTyping{to{transform:translateY(-.35rem);opacity:.45}}
#elderly-assistant .assistant-replies{border-top:1px solid #e1e7dc;background:rgba(249,251,247,.95);padding:.7rem 1rem}
#elderly-assistant .assistant-reply-card{display:flex;min-height:5.7rem;align-items:center;gap:.8rem;border:1px solid #d7e0ce;border-radius:1.15rem;background:white;padding:.9rem;text-align:right;box-shadow:0 6px 16px rgba(49,66,30,.06);transition:.2s}
#elderly-assistant .assistant-reply-card:hover{transform:translateY(-3px);border-color:#91a57b;box-shadow:0 12px 24px rgba(49,66,30,.12)}
#elderly-assistant .assistant-reply-card .icon{display:flex;width:2.9rem;height:2.9rem;flex:none;align-items:center;justify-content:center;border-radius:.9rem;background:#eaf1e3;color:#48612f;font-size:1.15rem}
#elderly-assistant .assistant-reply-card strong,#elderly-assistant .assistant-reply-card small{display:block}#elderly-assistant .assistant-reply-card strong{font-size:.95rem;color:#26351a}#elderly-assistant .assistant-reply-card small{margin-top:.2rem;font-size:.7rem;font-weight:700;color:#77816e}
#elderly-assistant .tracking .icon{background:#e9f2fb;color:#3b6d9c}#elderly-assistant .notifications .icon{background:#fff3dc;color:#a66c12}
#elderly-assistant .assistant-service-option{display:grid;grid-template-columns:1fr 3rem;min-height:4.8rem;overflow:hidden;border:1px solid #d8e1cf;border-radius:1rem;background:white;box-shadow:0 4px 12px rgba(49,66,30,.05);transition:.2s}#elderly-assistant .assistant-service-option:hover,#elderly-assistant .assistant-service-option.selected{border-color:#718256;background:#edf3e7;box-shadow:0 7px 17px rgba(49,66,30,.11)}#elderly-assistant .assistant-service-chip{display:flex;min-width:0;align-items:center;justify-content:center;gap:.55rem;padding:.7rem;font-size:.9rem;color:#31421e}
#elderly-assistant .assistant-service-chip span{font-size:1.4rem}#elderly-assistant .assistant-service-sound{display:flex;align-items:center;justify-content:center;border-right:1px solid #d8e1cf;background:#f4f8f0;color:#526b39;font-size:1rem;transition:.2s}#elderly-assistant .assistant-service-sound:hover{background:#31421e;color:white}
#elderly-assistant .assistant-appointment-voice{display:grid;gap:.55rem;margin-bottom:.75rem}#elderly-assistant .assistant-appointment-mic{display:flex;width:100%;align-items:center;gap:.75rem;border:1px solid #9db88a;border-radius:1rem;background:#edf5e7;padding:.75rem;text-align:right;color:#304c1d;transition:.2s}#elderly-assistant .assistant-appointment-mic>span:first-child{display:grid;width:2.8rem;height:2.8rem;flex:none;place-items:center;border-radius:.85rem;background:#355321;color:#fff;font-size:1.05rem}#elderly-assistant .assistant-appointment-mic strong,#elderly-assistant .assistant-appointment-mic small{display:block}#elderly-assistant .assistant-appointment-mic strong{font-size:.85rem;font-weight:900}#elderly-assistant .assistant-appointment-mic small{margin-top:.15rem;font-size:.68rem;color:#718064}#elderly-assistant .assistant-appointment-mic.active{border-color:#ef4444;background:#fff1f2;color:#b91c1c}#elderly-assistant .assistant-appointment-mic.active>span:first-child{background:#dc2626;animation:assistantPulse 1s infinite}#elderly-assistant .assistant-replay-date{display:inline-flex;min-height:2.5rem;align-items:center;justify-content:center;gap:.45rem;border:1px solid #c8d8bc;border-radius:.75rem;background:white;color:#405d2d;font-size:.75rem;font-weight:900}#elderly-assistant .assistant-appointment-voice p{border-radius:.7rem;background:#f3f7ef;padding:.5rem .7rem;color:#4c633d;font-size:.72rem}#elderly-assistant .assistant-date-divider{display:flex;align-items:center;gap:.6rem;margin:.7rem 0;color:#8b9584;font-size:.65rem;font-weight:800}#elderly-assistant .assistant-date-divider:before,#elderly-assistant .assistant-date-divider:after{height:1px;flex:1;background:#e1e7dc;content:""}
#elderly-assistant .assistant-suggestion{display:inline-flex;min-height:2.9rem;align-items:center;gap:.45rem;border:1px solid #bdcdb0;border-radius:2rem;background:#edf3e7;padding:.55rem 1rem;font-size:.8rem;font-weight:900;color:#3c5527}
#elderly-assistant .assistant-suggestion.muted{border-color:#d7dde1;background:white;color:#64748b}
#elderly-assistant .assistant-big-reply{display:flex;min-height:4.2rem;align-items:center;justify-content:center;gap:.6rem;border:1px solid #d2ddc8;border-radius:1rem;background:white;padding:.8rem;font-size:.95rem;font-weight:900;color:#31421e;transition:.2s}
#elderly-assistant .assistant-big-reply:hover{border-color:#718256;background:#edf3e7;transform:translateY(-2px)}#elderly-assistant .assistant-choice-sound{margin-right:auto;display:flex;width:2rem;height:2rem;align-items:center;justify-content:center;border-radius:.65rem;background:#edf3e7;color:#526b39;font-size:.75rem}
#elderly-assistant .assistant-date-box{border:1px solid #d8e1cf;border-radius:1rem;background:white;padding:1rem}#elderly-assistant .assistant-date-heading{display:flex;align-items:center;gap:.7rem;margin-bottom:.85rem}#elderly-assistant .assistant-date-heading>span{display:grid;width:2.7rem;height:2.7rem;flex:none;place-items:center;border-radius:.8rem;background:#355321;color:#fff}#elderly-assistant .assistant-date-heading strong,#elderly-assistant .assistant-date-heading small{display:block}#elderly-assistant .assistant-date-heading strong{color:#31421e;font-size:.9rem;font-weight:900}#elderly-assistant .assistant-date-heading small{margin-top:.15rem;color:#78836e;font-size:.7rem}#elderly-assistant .assistant-date-box label{display:block;margin-bottom:.5rem;font-size:.8rem;font-weight:900;color:#52643a}#elderly-assistant .assistant-date-box label b{margin-right:.3rem;border-radius:999px;background:#f3e8d6;padding:.12rem .45rem;color:#8b6227;font-size:.62rem}#elderly-assistant .assistant-date-box input{min-height:3rem;flex:1;border:1px solid #cbd5c0;border-radius:.8rem;font-size:.9rem}
#elderly-assistant .assistant-action{display:flex;min-height:3.6rem;align-items:center;justify-content:center;gap:.5rem;border-radius:1rem;font-size:.9rem;font-weight:900;transition:.2s}
#elderly-assistant .assistant-action.listen{border:1px solid #b7d2eb;background:#edf6ff;color:#285c88}#elderly-assistant .assistant-action.edit{border:1px solid #d1d9ca;background:white;color:#52643a}#elderly-assistant .assistant-action.cancel{border:1px solid #fecaca;background:#fff1f2;color:#be123c}#elderly-assistant .assistant-action.confirm{background:linear-gradient(135deg,#31421e,#58763b);color:white;box-shadow:0 8px 20px rgba(49,66,30,.23)}
#elderly-assistant .assistant-error{margin-top:.75rem;border:1px solid #fecaca;border-radius:.9rem;background:#fff1f2;padding:.75rem;font-size:.8rem;font-weight:900;color:#be123c}
#elderly-assistant .assistant-composer{display:flex;align-items:flex-end;gap:.55rem;border-top:1px solid #dbe3d4;background:white;padding:.7rem 1rem;box-shadow:0 -8px 24px rgba(49,66,30,.04)}
#elderly-assistant .assistant-composer textarea{min-height:3.3rem;max-height:7rem;flex:1;resize:none;border:1px solid #ced9c4;border-radius:1.1rem;background:#f7f9f5;padding:.85rem 1rem;font-size:.95rem;line-height:1.6}
#elderly-assistant .assistant-composer textarea:focus{border-color:#718256;box-shadow:0 0 0 4px rgba(113,130,86,.13);outline:none}
#elderly-assistant .assistant-mic,#elderly-assistant .assistant-send{display:flex;min-height:3.3rem;align-items:center;justify-content:center;border-radius:1rem;font-weight:900;transition:.2s}
#elderly-assistant .assistant-mic{width:3.3rem;flex:none;border:1px solid #d3ddca;background:#eff4eb;color:#52643a}#elderly-assistant .assistant-mic.active{background:#fee2e2;color:#dc2626;animation:assistantPulse 1s infinite}
#elderly-assistant .assistant-send{gap:.45rem;background:#31421e;padding:.75rem 1.1rem;color:white;box-shadow:0 6px 16px rgba(49,66,30,.22)}#elderly-assistant .assistant-send:disabled{cursor:not-allowed;opacity:.4}#elderly-assistant .assistant-send.compact{min-height:3rem;white-space:nowrap;font-size:.8rem}
#elderly-assistant .assistant-footer{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:.65rem;border-top:1px solid #e4e9df;background:#f8faf6;padding:.65rem 1rem;text-align:center;font-size:.68rem;font-weight:700;color:#6f7969}#elderly-assistant .assistant-footer span{display:inline-flex;align-items:center;gap:.35rem}#elderly-assistant .assistant-footer span:first-child{color:#526b39;font-weight:900}#elderly-assistant .assistant-footer-divider{width:1px;height:1rem;background:#d5ddcf}
@keyframes assistantPulse{50%{transform:scale(1.07)}}
@media(max-width:640px){#elderly-assistant.assistant-shell{min-height:calc(100vh - 2rem);border-radius:1.25rem}#elderly-assistant .assistant-topbar{padding:1rem}#elderly-assistant .assistant-avatar{width:3.6rem;height:3.6rem;border-radius:1.05rem}#elderly-assistant .assistant-messages{height:360px;padding:1rem}#elderly-assistant .assistant-message-row{max-width:95%}#elderly-assistant .assistant-replies,#elderly-assistant .assistant-composer{padding:.85rem 1rem}#elderly-assistant .assistant-composer textarea{font-size:.85rem}}
/* Compact, calm assistant layout */
#elderly-assistant.assistant-shell{width:min(100%,50rem);min-height:0;border-radius:1.25rem;box-shadow:0 14px 38px rgba(42,61,24,.12)}
#elderly-assistant .assistant-topbar{gap:.65rem;padding:.65rem .8rem;box-shadow:0 6px 20px rgba(35,52,20,.18)}
#elderly-assistant .assistant-avatar{width:3rem;height:3rem;border-radius:.85rem;font-size:1.2rem;box-shadow:inset 0 1px 0 rgba(255,255,255,.25),0 6px 14px rgba(0,0,0,.14)}
#elderly-assistant .assistant-avatar-pulse{width:.8rem;height:.8rem;border-width:.15rem;box-shadow:0 0 0 .18rem rgba(110,231,183,.15)}
#elderly-assistant .assistant-header-button{min-height:2.1rem;padding:.35rem .55rem;border-radius:.65rem;font-size:.68rem}
#elderly-assistant .assistant-chat{min-height:0}
#elderly-assistant .assistant-progress-wrap{padding:.55rem 1rem}
#elderly-assistant .assistant-progress{height:.28rem;margin-top:.4rem}
#elderly-assistant .assistant-messages{height:175px;padding:.8rem 1rem}
#elderly-assistant .assistant-message-row{max-width:88%;gap:.45rem;margin-bottom:.55rem}
#elderly-assistant .assistant-mini-avatar{width:1.9rem;height:1.9rem;border-radius:.65rem;font-size:.72rem}
#elderly-assistant .assistant-bubble{border-radius:1rem .3rem 1rem 1rem;padding:.55rem 2.35rem .55rem .75rem;box-shadow:0 4px 12px rgba(49,66,30,.06)}
#elderly-assistant .assistant-bubble p{font-size:.82rem;line-height:1.55}
#elderly-assistant .user .assistant-bubble{padding:.6rem .8rem}
#elderly-assistant .assistant-read-message{left:.5rem;top:.45rem;width:1.65rem;height:1.65rem;border-radius:.55rem;font-size:.62rem}
#elderly-assistant .assistant-replies{padding:.6rem .8rem}
#elderly-assistant .assistant-reply-card{min-height:4.35rem;gap:.6rem;border-radius:.9rem;padding:.65rem;box-shadow:0 4px 12px rgba(49,66,30,.05)}
#elderly-assistant .assistant-reply-card .icon{width:2.35rem;height:2.35rem;border-radius:.7rem;font-size:.95rem}
#elderly-assistant .assistant-reply-card strong{font-size:.82rem}
#elderly-assistant .assistant-reply-card small{margin-top:.12rem;font-size:.62rem}
#elderly-assistant .assistant-service-option{grid-template-columns:1fr 2.5rem;min-height:3.65rem;border-radius:.8rem}
#elderly-assistant .assistant-service-chip{gap:.45rem;padding:.55rem;font-size:.8rem}
#elderly-assistant .assistant-service-chip span{font-size:1.15rem}
#elderly-assistant .assistant-appointment-voice{display:grid;gap:.55rem;margin-bottom:.75rem}#elderly-assistant .assistant-appointment-mic{display:flex;width:100%;align-items:center;gap:.75rem;border:1px solid #9db88a;border-radius:1rem;background:#edf5e7;padding:.75rem;text-align:right;color:#304c1d;transition:.2s}#elderly-assistant .assistant-appointment-mic>span:first-child{display:grid;width:2.8rem;height:2.8rem;flex:none;place-items:center;border-radius:.85rem;background:#355321;color:#fff;font-size:1.05rem}#elderly-assistant .assistant-appointment-mic strong,#elderly-assistant .assistant-appointment-mic small{display:block}#elderly-assistant .assistant-appointment-mic strong{font-size:.85rem;font-weight:900}#elderly-assistant .assistant-appointment-mic small{margin-top:.15rem;font-size:.68rem;color:#718064}#elderly-assistant .assistant-appointment-mic.active{border-color:#ef4444;background:#fff1f2;color:#b91c1c}#elderly-assistant .assistant-appointment-mic.active>span:first-child{background:#dc2626;animation:assistantPulse 1s infinite}#elderly-assistant .assistant-replay-date{display:inline-flex;min-height:2.5rem;align-items:center;justify-content:center;gap:.45rem;border:1px solid #c8d8bc;border-radius:.75rem;background:white;color:#405d2d;font-size:.75rem;font-weight:900}#elderly-assistant .assistant-appointment-voice p{border-radius:.7rem;background:#f3f7ef;padding:.5rem .7rem;color:#4c633d;font-size:.72rem}#elderly-assistant .assistant-date-divider{display:flex;align-items:center;gap:.6rem;margin:.7rem 0;color:#8b9584;font-size:.65rem;font-weight:800}#elderly-assistant .assistant-date-divider:before,#elderly-assistant .assistant-date-divider:after{height:1px;flex:1;background:#e1e7dc;content:""}
#elderly-assistant .assistant-suggestion{min-height:2.4rem;padding:.4rem .75rem;font-size:.72rem}
#elderly-assistant .assistant-big-reply{min-height:3.35rem;padding:.6rem;font-size:.82rem}
#elderly-assistant .assistant-action{min-height:3rem;border-radius:.8rem;font-size:.78rem}
#elderly-assistant .assistant-composer{gap:.5rem;padding:.6rem .8rem}
#elderly-assistant .assistant-composer textarea{min-height:2.7rem;max-height:5rem;border-radius:.85rem;padding:.65rem .8rem;font-size:.82rem;line-height:1.5}
#elderly-assistant .assistant-mic,#elderly-assistant .assistant-send{min-height:2.7rem;border-radius:.8rem}
#elderly-assistant .assistant-mic{width:2.7rem}
#elderly-assistant .assistant-send{padding:.55rem .85rem}
#elderly-assistant .assistant-send.compact{min-height:2.7rem;font-size:.75rem}
#elderly-assistant .assistant-footer{gap:.45rem;padding:.4rem .65rem;font-size:.58rem}
@media(max-width:640px){#elderly-assistant.assistant-shell{min-height:0;width:100%;border-radius:1rem}#elderly-assistant .assistant-topbar{padding:.6rem}#elderly-assistant .assistant-avatar{width:2.7rem;height:2.7rem}#elderly-assistant .assistant-messages{height:210px;padding:.75rem}#elderly-assistant .assistant-replies,#elderly-assistant .assistant-composer{padding:.55rem .65rem}}
</style>
