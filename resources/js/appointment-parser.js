const ARABIC_DIGITS = '٠١٢٣٤٥٦٧٨٩';

const HOUR_WORDS = new Map([
    ['واحد', 1], ['واحده', 1], ['اولى', 1],
    ['اثنين', 2], ['اثنان', 2], ['اثنتين', 2], ['ثنتين', 2], ['ثانيه', 2],
    ['ثلاث', 3], ['ثلاثه', 3], ['ثالثه', 3],
    ['اربع', 4], ['اربعه', 4], ['رابعه', 4],
    ['خمس', 5], ['خمسه', 5], ['خامسه', 5],
    ['ست', 6], ['سته', 6], ['سادسه', 6],
    ['سبع', 7], ['سبعه', 7], ['سابعه', 7],
    ['ثمان', 8], ['ثمانيه', 8], ['ثامنه', 8],
    ['تسع', 9], ['تسعه', 9], ['تاسعه', 9],
    ['عشر', 10], ['عشره', 10], ['عاشره', 10],
    ['احدعش', 11], ['احدعشر', 11], ['حاديه عشر', 11],
    ['اثنعش', 12], ['اثنا عشر', 12], ['ثانيه عشر', 12],
]);

const WEEKDAYS = new Map([
    ['الاحد', 0],
    ['الاثنين', 1],
    ['الثلاثاء', 2],
    ['الاربعاء', 3],
    ['الخميس', 4],
    ['الجمعه', 5],
    ['السبت', 6],
]);

const pad = (value) => String(value).padStart(2, '0');

const normalizeArabic = (value) => String(value || '')
    .replace(/[٠-٩]/g, (digit) => String(ARABIC_DIGITS.indexOf(digit)))
    .replace(/[أإآ]/g, 'ا')
    .replace(/ة/g, 'ه')
    .replace(/[ًٌٍَُِّْـ]/g, '')
    .replace(/[،,.!?؟]/g, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();

const wordNumber = (token) => {
    const clean = token.replace(/^ال/, '');
    return HOUR_WORDS.get(clean) ?? HOUR_WORDS.get(token) ?? null;
};

const resolveDate = (text, now) => {
    const date = new Date(now.getFullYear(), now.getMonth(), now.getDate());

    if (/بعد (?:بكرا|غد|الغد)/.test(text)) {
        date.setDate(date.getDate() + 2);
        return date;
    }
    if (/(?:^|\s)(?:بكرا|غدا|غد)(?:\s|$)/.test(text)) {
        date.setDate(date.getDate() + 1);
        return date;
    }
    if (/(?:^|\s)اليوم(?:\s|$)/.test(text)) return date;

    for (const [name, weekday] of WEEKDAYS) {
        if (!text.includes(name)) continue;
        let difference = (weekday - date.getDay() + 7) % 7;
        if (difference === 0) difference = 7;
        date.setDate(date.getDate() + difference);
        return date;
    }

    return null;
};

const resolveTime = (text) => {
    const marker = text.search(/الساعه|ساعه/);
    const colloquial = text.match(/(?:^|\s)(?:عال|ع\s+)([^\s]+)(.*)$/);
    if (marker === -1 && !colloquial) {
        return { error: 'احكي الساعة، مثل: الساعة ثلاثة العصر.' };
    }

    const timeText = marker === -1
        ? (colloquial[1] + colloquial[2]).trim()
        : text.slice(marker).replace(/^(?:الساعه|ساعه)\s*/, '');
    const tokens = timeText.split(' ');
    const numeric = timeText.match(/^(\d{1,2})(?::(\d{1,2}))?/);
    let hour = numeric ? Number(numeric[1]) : wordNumber(tokens[0]);
    let minute = numeric?.[2] ? Number(numeric[2]) : 0;

    if (hour === null || Number.isNaN(hour) || hour > 23) {
        return { error: 'ما قدرت أحدد الساعة. احكي مثلًا: الساعة ثلاثة العصر.' };
    }

    if (/ونص|والنصف|نصف/.test(timeText)) minute = 30;
    else if (/وربع|والربع/.test(timeText)) minute = 15;
    else if (/الا ربع/.test(timeText)) {
        hour -= 1;
        minute = 45;
    }

    if (minute > 59) return { error: 'الدقائق غير صحيحة. حاول مرة ثانية.' };

    const morning = /صباح|الصبح|الفجر/.test(text);
    const afternoon = /العصر|المساء|مساء|الليل|ليل|بعد الظهر/.test(text);
    const noon = /الظهر|ظهرا/.test(text) && !/بعد الظهر/.test(text);

    if (hour <= 12 && !morning && !afternoon && !noon) {
        return { error: 'حدد إذا الساعة صباحًا أو مساءً.' };
    }

    if (morning && hour === 12) hour = 0;
    if ((afternoon || noon) && hour < 12) hour += 12;

    return { hour, minute };
};

export function parseArabicAppointment(input, now = new Date()) {
    const text = normalizeArabic(input);
    const date = resolveDate(text, now);

    if (!date) {
        return {
            ok: false,
            error: 'احكي اليوم مع الساعة، مثل: بكرا الساعة ثلاثة العصر.',
        };
    }

    const time = resolveTime(text);
    if (time.error) return { ok: false, error: time.error };

    date.setHours(time.hour, time.minute, 0, 0);
    if (date <= now) {
        return { ok: false, error: 'هذا الموعد مضى. اختار يومًا ووقتًا في المستقبل.' };
    }

    const scheduledAt = `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    const spokenDate = new Intl.DateTimeFormat('ar-PS', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
    }).format(date);

    return {
        ok: true,
        scheduledAt,
        date,
        spokenConfirmation: `فهمت عليك. الموعد ${spokenDate}. إذا صحيح اضغط تأكيد الموعد.`,
    };
}

export function appointmentVoiceKeys(date) {
    const hour = date.getHours();
    const hour12 = hour % 12 || 12;
    const minute = date.getMinutes();
    const period = hour < 12
        ? 'morning'
        : hour < 14
            ? 'noon'
            : hour < 18
                ? 'afternoon'
                : 'evening';

    return [
        'appointment_confirm_start',
        'appointment_weekday_' + date.getDay(),
        'appointment_day_' + date.getDate(),
        'appointment_month_' + (date.getMonth() + 1),
        'appointment_at_hour',
        'appointment_hour_' + hour12,
        minute ? 'appointment_minute_' + minute : null,
        'appointment_period_' + period,
        'appointment_confirm_end',
    ].filter(Boolean);
}