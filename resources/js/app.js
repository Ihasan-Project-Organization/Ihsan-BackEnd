import './bootstrap';
import './elderly-assistant';

/**
 * طبقة الصوت المشتركة في واجهة كبير السن.
 * التسجيلات الثابتة تعمل أولاً، ونطق المتصفح بديل عند غياب ملف MP3.
 */
window.IhsanVoice = (() => {
    let activeAudio = null;
    let activePlayback = null;
    let playbackGeneration = 0;
    const fixedClips = {
        welcome: '/audio/elderly-assistant/welcome-anis.mp3',
        service: '/audio/elderly-assistant/service-question.mp3',
        service_grocery: '/audio/elderly-assistant/service-grocery.mp3',
        service_medicine: '/audio/elderly-assistant/service-medicine.mp3',
        service_medical_escort: '/audio/elderly-assistant/service-medical-escort.mp3',
        service_social_visit: '/audio/elderly-assistant/service-social-visit.mp3',
        service_home_help: '/audio/elderly-assistant/service-home-help.mp3',
        service_support_request: '/audio/elderly-assistant/service-other.mp3',
        location: '/audio/elderly-assistant/location-question.mp3',
        timing: '/audio/elderly-assistant/timing-question.mp3',
        timing_immediate: '/audio/elderly-assistant/timing-immediate.mp3',
        timing_scheduled: '/audio/elderly-assistant/timing-scheduled.mp3',
        details: '/audio/elderly-assistant/details-question.mp3',
        review: '/audio/elderly-assistant/review-request.mp3',
        edit: '/audio/elderly-assistant/edit-question.mp3',
        success: '/audio/elderly-assistant/request-sent.mp3',
        cancel: '/audio/elderly-assistant/request-cancelled.mp3',
        unknown_intent: '/audio/elderly-assistant/unknown-intent.mp3',
        status_pending_acceptance: '/audio/elderly-assistant/status-pending-acceptance.mp3',
        status_accepted: '/audio/elderly-assistant/status-accepted.mp3',
        status_assigned: '/audio/elderly-assistant/status-assigned.mp3',
        status_in_progress: '/audio/elderly-assistant/status-in-progress.mp3',
        status_pending_confirmation: '/audio/elderly-assistant/status-pending-confirmation.mp3',
        status_completed: '/audio/elderly-assistant/status-completed.mp3',
        status_under_review: '/audio/elderly-assistant/status-under-review.mp3',
        status_no_provider_found: '/audio/elderly-assistant/status-no-provider-found.mp3',
        status_provider_apologized: '/audio/elderly-assistant/status-provider-apologized.mp3',
        status_provider_delayed: '/audio/elderly-assistant/status-provider-delayed.mp3',
        status_cancelled: '/audio/elderly-assistant/status-cancelled.mp3',
        button_new_chat: '/audio/elderly-assistant/button-new-chat.mp3',
        button_request: '/audio/elderly-assistant/button-request.mp3',
        button_tracking: '/audio/elderly-assistant/button-tracking.mp3',
        button_notifications: '/audio/elderly-assistant/button-notifications.mp3',
        button_use_city: '/audio/elderly-assistant/button-use-city.mp3',
        button_confirm_time: '/audio/elderly-assistant/button-confirm-time.mp3',
        button_call_arrival: '/audio/elderly-assistant/button-call-arrival.mp3',
        button_skip: '/audio/elderly-assistant/button-skip.mp3',
        button_read_summary: '/audio/elderly-assistant/button-read-summary.mp3',
        button_edit: '/audio/elderly-assistant/button-edit.mp3',
        button_cancel: '/audio/elderly-assistant/button-cancel.mp3',
        button_confirm_send: '/audio/elderly-assistant/button-confirm-send.mp3',
        button_microphone: '/audio/elderly-assistant/button-microphone.mp3',
        button_send: '/audio/elderly-assistant/button-send.mp3',
        button_listen: '/audio/elderly-assistant/button-listen.mp3',
    };

    const appointmentBase = '/audio/elderly-assistant/appointment';
    fixedClips.appointment_confirm_start = appointmentBase + '/confirm-start.mp3';
    fixedClips.appointment_at_hour = appointmentBase + '/at-hour.mp3';
    fixedClips.appointment_confirm_end = appointmentBase + '/confirm-end.mp3';
    fixedClips.appointment_error = appointmentBase + '/error.mp3';
    fixedClips.appointment_confirmed = appointmentBase + '/confirmed.mp3';
    fixedClips.appointment_prompt = appointmentBase + '/prompt.mp3';
    ['morning', 'noon', 'afternoon', 'evening'].forEach((period) => {
        fixedClips['appointment_period_' + period] = appointmentBase + '/period-' + period + '.mp3';
    });
    for (let index = 0; index < 7; index++) fixedClips['appointment_weekday_' + index] = appointmentBase + '/weekday-' + index + '.mp3';
    for (let index = 1; index <= 31; index++) fixedClips['appointment_day_' + index] = appointmentBase + '/day-' + index + '.mp3';
    for (let index = 1; index <= 12; index++) {
        fixedClips['appointment_month_' + index] = appointmentBase + '/month-' + index + '.mp3';
        fixedClips['appointment_hour_' + index] = appointmentBase + '/hour-' + index + '.mp3';
    }
    for (let index = 1; index < 60; index++) fixedClips['appointment_minute_' + index] = appointmentBase + '/minute-' + index + '.mp3';

    const stopCurrentPlayback = () => {
        if (activeAudio) {
            activeAudio.onended = null;
            activeAudio.onerror = null;
            activeAudio.pause();
            activeAudio.currentTime = 0;
            activeAudio = null;
        }
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();

        if (activePlayback) {
            const { resolve } = activePlayback;
            activePlayback = null;
            resolve(false);
        }
    };

    const beginPlayback = () => {
        stopCurrentPlayback();
        playbackGeneration += 1;
        return playbackGeneration;
    };

    const stop = () => {
        playbackGeneration += 1;
        stopCurrentPlayback();
    };

    let cachedArabicVoice = null;

    const chooseArabicVoice = () => {
        if (!('speechSynthesis' in window)) return null;

        const allVoices = window.speechSynthesis.getVoices() || [];
        if (!allVoices.length) return null;

        const arabicVoices = allVoices
            .filter((voice) => {
                const lang = (voice.lang || '').toLowerCase().replace('_', '-');
                const name = (voice.name || '').toLowerCase();
                return lang.startsWith('ar')
                    || name.includes('arabic')
                    || name.includes('العربية')
                    || name.includes('hamed')
                    || name.includes('حامد')
                    || name.includes('maged')
                    || name.includes('naayf')
                    || name.includes('نايف')
                    || name.includes('hoda')
                    || name.includes('هدى')
                    || name.includes('salma')
                    || name.includes('سلمى')
                    || name.includes('shakir')
                    || name.includes('شاكر')
                    || name.includes('tarik')
                    || name.includes('zeina');
            })
            .sort((first, second) => {
                const score = (voice) => {
                    const name = voice.name.toLowerCase();
                    const language = (voice.lang || '').toLowerCase().replace('_', '-');
                    let value = 0;
                    if (language === 'ar-sa') value += 100;
                    else if (language.startsWith('ar')) value += 80;
                    if (/natural|online|google/.test(name)) value += 50;
                    if (/hamed|حامد|naayf|نايف|salma|سلمى|shakir|شاكر|hoda|هدى/.test(name)) value += 40;
                    if (voice.localService) value += 10;
                    return value;
                };
                return score(second) - score(first);
            });

        if (arabicVoices.length > 0) {
            cachedArabicVoice = arabicVoices[0];
            return cachedArabicVoice;
        }

        return null;
    };

    const waitForArabicVoice = () => new Promise((resolve) => {
        const available = chooseArabicVoice();
        if (available) return resolve(available);

        let finished = false;
        const complete = () => {
            if (finished) return;
            finished = true;
            resolve(chooseArabicVoice());
        };

        window.speechSynthesis.addEventListener('voiceschanged', complete, { once: true });
        window.setTimeout(complete, 1200);
    });

    if ('speechSynthesis' in window) {
        chooseArabicVoice();
        window.speechSynthesis.addEventListener('voiceschanged', chooseArabicVoice);
    }

    const speakForGeneration = async (text, generation) => {
        if (!text || !('speechSynthesis' in window) || generation !== playbackGeneration) return false;

        const arabicVoice = cachedArabicVoice || await waitForArabicVoice();
        if (generation !== playbackGeneration) return false;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = arabicVoice?.lang || 'ar-SA';
        if (arabicVoice) {
            utterance.voice = arabicVoice;
        }
        utterance.rate = 0.85;
        utterance.pitch = 1;
        utterance.volume = 1;

        return new Promise((resolve) => {
            const finish = (completed) => {
                if (activePlayback?.generation === generation) activePlayback = null;
                resolve(completed);
            };

            activePlayback = { generation, resolve: () => finish(false) };
            utterance.onend = () => finish(true);
            utterance.onerror = () => finish(false);
            window.speechSynthesis.speak(utterance);
        });
    };

    const speak = (text) => speakForGeneration(text, beginPlayback());

    const playFixed = (key, fallbackText) => {
        const generation = beginPlayback();
        const source = fixedClips[key];
        if (!source) return speakForGeneration(fallbackText, generation);

        return new Promise((resolve) => {
            const audio = new Audio(source);
            let settled = false;

            const finish = (completed) => {
                if (settled) return;
                settled = true;
                if (activeAudio === audio) activeAudio = null;
                if (activePlayback?.generation === generation) activePlayback = null;
                resolve(completed);
            };

            const useFallback = async () => {
                if (settled || generation !== playbackGeneration) return finish(false);
                if (activeAudio === audio) activeAudio = null;
                if (activePlayback?.generation === generation) activePlayback = null;
                finish(await speakForGeneration(fallbackText, generation));
            };

            activeAudio = audio;
            activePlayback = { generation, resolve: () => finish(false) };
            audio.onended = () => finish(true);
            audio.onerror = useFallback;
            audio.play().catch(useFallback);
        });
    };

    return { playFixed, speakDynamic: speak, stop };
})();

// زر عام لقراءة البيانات المتغيرة القادمة من النظام.
document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-tts-text]');
    if (!button) return;
    event.preventDefault();

    if (button.dataset.ttsActive === 'true') {
        window.IhsanVoice.stop();
        return;
    }

    const label = button.querySelector('[data-tts-label]');
    const icon = button.querySelector('i');
    const idleText = label?.textContent || '';

    button.dataset.ttsActive = 'true';
    button.setAttribute('aria-busy', 'true');
    button.classList.add('is-playing');
    if (label) label.textContent = 'إيقاف القراءة';
    if (icon) {
        icon.classList.remove('fa-volume-high');
        icon.classList.add('fa-stop');
    }

    const voiceKey = button.dataset.voiceKey;
    try {
        if (voiceKey) {
            await window.IhsanVoice.playFixed(voiceKey, button.dataset.ttsText || '');
        } else {
            await window.IhsanVoice.speakDynamic(button.dataset.ttsText || '');
        }
    } finally {
        button.dataset.ttsActive = 'false';
        button.setAttribute('aria-busy', 'false');
        button.classList.remove('is-playing');
        if (label) label.textContent = idleText;
        if (icon) {
            icon.classList.remove('fa-stop');
            icon.classList.add('fa-volume-high');
        }
    }
});

/**
 * قراءة وظيفة جميع أزرار وروابط المساعد عند المرور عليها أو الوصول إليها.
 * يفيد كبير السن قبل تنفيذ الإجراء، ويعمل بالماوس ولوحة المفاتيح.
 */
(() => {
    let lastElement = null;
    let lastSpokenAt = 0;
    let pendingAnnouncement = null;

    const buttonClipByLabel = new Map([
        ['محادثة جديدة', 'button_new_chat'],
        ['طلب خدمة', 'button_request'],
        ['متابعة طلباتي', 'button_tracking'],
        ['الإشعارات', 'button_notifications'],
        ['استخدام مدينتي', 'button_use_city'],
        ['تأكيد الموعد', 'button_confirm_time'],
        ['اتصل بي عند الوصول', 'button_call_arrival'],
        ['تخطي، ما في تفاصيل', 'button_skip'],
        ['قراءة الملخص', 'button_read_summary'],
        ['تعديل', 'button_edit'],
        ['إلغاء الطلب', 'button_cancel'],
        ['تأكيد وإرسال', 'button_confirm_send'],
        ['استخدم الميكروفون', 'button_microphone'],
        ['إرسال', 'button_send'],
        ['استمع', 'button_listen'],
    ]);

    const getVoiceLabel = (element) => {
        const explicitLabel = element.dataset.voiceText || element.getAttribute('aria-label');
        const visibleLabel = element.innerText || element.textContent || '';
        return String(explicitLabel || visibleLabel)
            .replace(/\s+/g, ' ')
            .replace(/AI/g, 'المساعد الذكي')
            .trim();
    };

    const announceButton = (element) => {
        if (!element || !element.closest('#elderly-assistant')) return;
        if (element.matches('[data-voice-key], .assistant-read-message, .assistant-service-sound, .assistant-service-chip')) return;
        const now = Date.now();
        if (lastElement === element && now - lastSpokenAt < 1800) return;

        const label = getVoiceLabel(element);
        if (!label) return;
        lastElement = element;
        lastSpokenAt = now;
        const normalizedLabel = label.replace(/[🔊🎤➤✏️❌✅]/gu, '').trim();
        const clipKey = element.dataset.voiceKey || buttonClipByLabel.get(normalizedLabel);
        if (clipKey) {
            window.IhsanVoice?.playFixed(clipKey, normalizedLabel);
        } else {
            window.IhsanVoice?.speakDynamic(normalizedLabel);
        }
    };

    const cancelPendingAnnouncement = () => {
        if (!pendingAnnouncement) return;
        window.clearTimeout(pendingAnnouncement);
        pendingAnnouncement = null;
    };

    document.addEventListener('pointerover', (event) => {
        const element = event.target.closest('#elderly-assistant button, #elderly-assistant a');
        if (!element || (event.relatedTarget && element.contains(event.relatedTarget))) return;
        cancelPendingAnnouncement();
        pendingAnnouncement = window.setTimeout(() => {
            pendingAnnouncement = null;
            announceButton(element);
        }, 450);
    });

    document.addEventListener('pointerout', (event) => {
        const element = event.target.closest('#elderly-assistant button, #elderly-assistant a');
        if (!element || (event.relatedTarget && element.contains(event.relatedTarget))) return;
        cancelPendingAnnouncement();
    });

    document.addEventListener('focusin', (event) => {
        const element = event.target.closest('#elderly-assistant button, #elderly-assistant a');
        cancelPendingAnnouncement();
        announceButton(element);
    });

    document.addEventListener('click', cancelPendingAnnouncement, true);
})();

// تطبيق مقياس الخط المحفوظ مسبقاً عبر المنصة
try {
    const savedScale = localStorage.getItem('ihsan_font_scale');
    if (savedScale) {
        document.documentElement.style.fontSize = savedScale + '%';
    }
} catch (e) {}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
