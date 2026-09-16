import test from 'node:test';
import assert from 'node:assert/strict';
import { parseAppointmentWithAI } from '../../resources/js/appointment-ai.js';

test('returns the structured Gemini appointment without using the fallback', async () => {
    let fallbackCalled = false;
    const result = await parseAppointmentWithAI('بكرا الساعة ثلاثة العصر', {
        csrfToken: 'csrf-token',
        fetchImpl: async (url, options) => {
            assert.equal(url, '/assistant/appointments/parse');
            assert.equal(options.headers['X-CSRF-TOKEN'], 'csrf-token');
            assert.deepEqual(JSON.parse(options.body), { transcript: 'بكرا الساعة ثلاثة العصر' });
            return {
                ok: true,
                json: async () => ({ ok: true, scheduled_at: '2026-09-17T15:00', source: 'gemini' }),
            };
        },
        fallback: () => {
            fallbackCalled = true;
            return { ok: false };
        },
    });

    assert.equal(result.scheduledAt, '2026-09-17T15:00');
    assert.equal(result.source, 'gemini');
    assert.equal(fallbackCalled, false);
});

test('keeps Gemini clarification instead of guessing locally', async () => {
    const result = await parseAppointmentWithAI('الساعة ثلاثة', {
        fetchImpl: async () => ({
            ok: true,
            json: async () => ({ ok: false, message: 'أي يوم تقصد؟', source: 'gemini' }),
        }),
        fallback: () => ({ ok: true, scheduledAt: '2099-01-01T15:00' }),
    });

    assert.deepEqual(result, {
        ok: false,
        error: 'أي يوم تقصد؟',
        source: 'gemini',
    });
});

test('uses the local parser when Gemini is unavailable', async () => {
    const result = await parseAppointmentWithAI('بكرا الساعة ثلاثة العصر', {
        fetchImpl: async () => ({ ok: false, status: 503, json: async () => ({}) }),
        fallback: () => ({ ok: true, scheduledAt: '2026-09-17T15:00', date: new Date(2026, 8, 17, 15) }),
    });

    assert.equal(result.ok, true);
    assert.equal(result.source, 'local');
});

test('uses the local parser after a network error', async () => {
    const result = await parseAppointmentWithAI('بكرا الساعة ثلاثة العصر', {
        fetchImpl: async () => { throw new Error('offline'); },
        fallback: () => ({ ok: false, error: 'احكي اليوم والساعة مرة ثانية.' }),
    });

    assert.equal(result.ok, false);
    assert.equal(result.error, 'احكي اليوم والساعة مرة ثانية.');
    assert.equal(result.source, 'local');
});
