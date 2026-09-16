import test from 'node:test';
import assert from 'node:assert/strict';

import { startArabicSpeechRecognition } from '../../resources/js/speech-recognition.js';

class FakeRecognition {
    constructor() {
        FakeRecognition.instance = this;
    }

    start() {
        this.started = true;
    }
}

test('stops platform audio and configures Arabic recognition before listening', () => {
    const events = [];

    startArabicSpeechRecognition({
        Recognition: FakeRecognition,
        stopAudio: () => events.push('audio-stopped'),
        onListeningChange: (value) => events.push(value ? 'listening' : 'stopped'),
        onTranscript: () => {},
        onError: () => {},
    });

    const recognition = FakeRecognition.instance;
    assert.deepEqual(events.slice(0, 2), ['audio-stopped', 'listening']);
    assert.equal(recognition.lang, 'ar-PS');
    assert.equal(recognition.continuous, false);
    assert.equal(recognition.interimResults, false);
    assert.equal(recognition.maxAlternatives, 1);
    assert.equal(recognition.started, true);
});

test('returns the recognized Arabic text and stops the listening state', () => {
    const transcripts = [];
    const listeningStates = [];

    startArabicSpeechRecognition({
        Recognition: FakeRecognition,
        stopAudio: () => {},
        onListeningChange: (value) => listeningStates.push(value),
        onTranscript: (text) => transcripts.push(text),
        onError: () => {},
    });

    FakeRecognition.instance.onresult({ results: [[{ transcript: '  بدي شراء دواء  ' }]] });
    FakeRecognition.instance.onend();

    assert.deepEqual(transcripts, ['بدي شراء دواء']);
    assert.deepEqual(listeningStates, [true, false]);
});

test('shows a specific Arabic message when microphone permission is denied', () => {
    const errors = [];

    startArabicSpeechRecognition({
        Recognition: FakeRecognition,
        stopAudio: () => {},
        onListeningChange: () => {},
        onTranscript: () => {},
        onError: (message) => errors.push(message),
    });

    FakeRecognition.instance.onerror({ error: 'not-allowed' });

    assert.deepEqual(errors, ['اسمح باستخدام الميكروفون من إعدادات المتصفح، ثم حاول مرة ثانية.']);
});