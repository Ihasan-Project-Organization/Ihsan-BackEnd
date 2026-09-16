<?php

use App\Models\ServiceProviderProfile;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Carbon::setTestNow('2026-09-16 10:00:00');
    config([
        'app.timezone' => 'Asia/Hebron',
        'services.gemini.key' => 'test-server-key',
        'services.gemini.model' => 'gemini-3.8-flash',
    ]);
});

afterEach(fn () => Carbon::setTestNow());

test('guest cannot use the appointment parser', function () {
    $this->postJson('/assistant/appointments/parse', [
        'transcript' => 'بكرا الساعة ثلاثة العصر',
    ])->assertUnauthorized();
});

test('service provider cannot use the elderly appointment parser', function () {
    $provider = User::factory()->create();
    ServiceProviderProfile::create([
        'user_id' => $provider->id,
        'full_name' => $provider->name,
        'birth_date' => '1990-01-01',
        'phone_number' => '0599000000',
        'id_document_path' => 'documents/id.pdf',
        'good_conduct_cert_path' => 'documents/conduct.pdf',
    ]);

    $this->actingAs($provider)
        ->postJson('/assistant/appointments/parse', [
            'transcript' => 'بكرا الساعة ثلاثة العصر',
        ])
        ->assertForbidden();
});

test('elder can turn an Arabic voice transcript into a validated appointment', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => ['parts' => [[
                    'text' => json_encode([
                        'resolved' => true,
                        'date' => '2026-09-17',
                        'time' => '15:30',
                        'clarification' => null,
                    ], JSON_UNESCAPED_UNICODE),
                ]]],
            ]],
        ]),
    ]);

    $elder = User::factory()->create();

    $this->actingAs($elder)
        ->postJson('/assistant/appointments/parse', [
            'transcript' => 'بكرا الساعة ثلاثة ونص العصر',
        ])
        ->assertOk()
        ->assertJson([
            'ok' => true,
            'scheduled_at' => '2026-09-17T15:30',
            'source' => 'gemini',
        ]);

    Http::assertSent(function ($request) {
        return $request->hasHeader('x-goog-api-key', 'test-server-key')
            && ! str_contains($request->url(), 'test-server-key')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Asia/Hebron');
    });
});

test('ambiguous speech returns a short Arabic clarification without inventing a date', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => ['parts' => [[
                    'text' => json_encode([
                        'resolved' => false,
                        'date' => null,
                        'time' => null,
                        'clarification' => 'أي يوم تقصد؟',
                    ], JSON_UNESCAPED_UNICODE),
                ]]],
            ]],
        ]),
    ]);

    $elder = User::factory()->create();

    $this->actingAs($elder)
        ->postJson('/assistant/appointments/parse', ['transcript' => 'الساعة ثلاثة'])
        ->assertOk()
        ->assertJson([
            'ok' => false,
            'message' => 'أي يوم تقصد؟',
            'source' => 'gemini',
        ]);
});

test('invalid or past Gemini output is rejected', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => ['parts' => [[
                    'text' => '{"resolved":true,"date":"2026-09-15","time":"15:00","clarification":null}',
                ]]],
            ]],
        ]),
    ]);

    $elder = User::factory()->create();

    $this->actingAs($elder)
        ->postJson('/assistant/appointments/parse', [
            'transcript' => 'امبارح الساعة ثلاثة العصر',
        ])
        ->assertStatus(422)
        ->assertJsonPath('ok', false);
});

test('the transcript is required and tightly limited', function () {
    $elder = User::factory()->create();

    $this->actingAs($elder)
        ->postJson('/assistant/appointments/parse', ['transcript' => str_repeat('ا', 501)])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('transcript');

    Http::assertNothingSent();
});
