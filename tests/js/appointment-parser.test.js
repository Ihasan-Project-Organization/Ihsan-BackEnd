import test from 'node:test';
import assert from 'node:assert/strict';

import { appointmentVoiceKeys, parseArabicAppointment } from '../../resources/js/appointment-parser.js';

const now = () => new Date(2026, 8, 16, 10, 0, 0);

test('parses tomorrow at three in the afternoon', () => {
    const result = parseArabicAppointment('بكرا الساعة ثلاثة العصر', now());

    assert.equal(result.ok, true);
    assert.equal(result.scheduledAt, '2026-09-17T15:00');
});

test('parses colloquial Palestinian phrasing without the word hour', () => {
    const result = parseArabicAppointment('بكرا عالثلاثة العصر', now());

    assert.equal(result.ok, true);
    assert.equal(result.scheduledAt, '2026-09-17T15:00');
});
test('parses the day after tomorrow at four thirty', () => {
    const result = parseArabicAppointment('بعد بكرا الساعة أربعة ونص العصر', now());

    assert.equal(result.ok, true);
    assert.equal(result.scheduledAt, '2026-09-18T16:30');
});

test('parses an upcoming weekday and Arabic digits', () => {
    const result = parseArabicAppointment('الخميس الساعة ١٠ الصبح', now());

    assert.equal(result.ok, true);
    assert.equal(result.scheduledAt, '2026-09-17T10:00');
});

test('asks for clarification when morning or evening is missing', () => {
    const result = parseArabicAppointment('بكرا الساعة ثلاثة', now());

    assert.equal(result.ok, false);
    assert.equal(result.error, 'حدد إذا الساعة صباحًا أو مساءً.');
});

test('rejects speech that does not include a day', () => {
    const result = parseArabicAppointment('الساعة خمسة العصر', now());

    assert.equal(result.ok, false);
    assert.equal(result.error, 'احكي اليوم مع الساعة، مثل: بكرا الساعة ثلاثة العصر.');
});

test('builds the Arabic audio sequence for the exact appointment', () => {
    const keys = appointmentVoiceKeys(new Date(2026, 8, 17, 15, 30));

    assert.deepEqual(keys, [
        'appointment_confirm_start',
        'appointment_weekday_4',
        'appointment_day_17',
        'appointment_month_9',
        'appointment_at_hour',
        'appointment_hour_3',
        'appointment_minute_30',
        'appointment_period_afternoon',
        'appointment_confirm_end',
    ]);
});