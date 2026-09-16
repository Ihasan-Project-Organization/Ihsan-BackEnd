<?php

namespace App\Http\Controllers;

use App\Services\GeminiAppointmentParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use UnexpectedValueException;

class AssistantAppointmentController extends Controller
{
    public function __invoke(Request $request, GeminiAppointmentParser $parser): JsonResponse
    {
        $validated = $request->validate([
            'transcript' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        try {
            return response()->json($parser->parse($validated['transcript']));
        } catch (UnexpectedValueException $exception) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
                'source' => 'gemini',
            ], 422);
        } catch (Throwable) {
            return response()->json([
                'ok' => false,
                'message' => 'خدمة فهم الموعد غير متاحة الآن.',
                'source' => 'gemini',
            ], 503);
        }
    }
}
