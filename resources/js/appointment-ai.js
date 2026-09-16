const normalizeFallback = (result) => ({
    ...result,
    source: 'local',
});

export async function parseAppointmentWithAI(transcript, options = {}) {
    const fetchImpl = options.fetchImpl || window.fetch.bind(window);
    const fallback = options.fallback;
    const csrfToken = options.csrfToken
        ?? globalThis.document?.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        ?? '';

    try {
        const response = await fetchImpl('/assistant/appointments/parse', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ transcript }),
        });

        const payload = await response.json().catch(() => ({}));

        if (response.ok && payload.ok === true && typeof payload.scheduled_at === 'string') {
            return {
                ok: true,
                scheduledAt: payload.scheduled_at,
                date: new Date(payload.scheduled_at),
                source: 'gemini',
            };
        }

        if (response.ok && payload.ok === false) {
            return {
                ok: false,
                error: payload.message || 'احكي اليوم والساعة مرة ثانية.',
                source: 'gemini',
            };
        }
    } catch (_) {
        // The local parser below keeps appointment selection usable offline.
    }

    return normalizeFallback(fallback(transcript));
}
