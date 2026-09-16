const recognitionErrorMessages = {
    'not-allowed': 'اسمح باستخدام الميكروفون من إعدادات المتصفح، ثم حاول مرة ثانية.',
    'service-not-allowed': 'اسمح باستخدام الميكروفون من إعدادات المتصفح، ثم حاول مرة ثانية.',
    'no-speech': 'ما سمعت كلامًا. اضغط الميكروفون واحكي بصوت واضح.',
    'audio-capture': 'تعذر الوصول إلى الميكروفون. تأكد أنه متصل وغير مستخدم في برنامج آخر.',
    'network': 'التعرف على الصوت يحتاج اتصالًا بالإنترنت. تحقق من الشبكة وحاول مرة ثانية.',
};

export function startArabicSpeechRecognition({
    Recognition,
    stopAudio,
    onListeningChange,
    onTranscript,
    onError,
}) {
    stopAudio?.();

    const recognition = new Recognition();
    recognition.lang = 'ar-PS';
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onresult = (event) => {
        const transcript = event.results?.[0]?.[0]?.transcript?.trim();
        if (transcript) onTranscript(transcript);
    };

    recognition.onerror = (event) => {
        const message = recognitionErrorMessages[event.error]
            || 'ما قدرت أتعرف على الكلام. حاول مرة ثانية.';
        onListeningChange(false);
        onError(message);
    };

    recognition.onend = () => onListeningChange(false);

    try {
        onListeningChange(true);
        recognition.start();
    } catch (_) {
        onListeningChange(false);
        onError('تعذر تشغيل الميكروفون. انتظر لحظة ثم حاول مرة ثانية.');
    }

    return recognition;
}