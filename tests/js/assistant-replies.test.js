import test from 'node:test';
import assert from 'node:assert/strict';

import { assistantReplies } from '../../resources/js/assistant-replies.js';

test('unknown intent reply has a fixed Arabic voice key', () => {
    assert.equal(
        assistantReplies.unknown.text,
        'بقدر أساعدك بطلب خدمة، متابعة طلباتك، أو قراءة الإشعارات. جرّب تحكي: بدي أطلب دواء.',
    );
    assert.equal(assistantReplies.unknown.voiceKey, 'unknown_intent');
});
