<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            if ($user->status === 'pending') {
                return redirect()->route('auth.pending');
            }

            $targetRoute = $user->isProvider() ? 'provider.dashboard' : 'dashboard';
            return redirect()->intended(route($targetRoute, absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // بعد تأكيد البريد فعلياً: إذا كانت الحالة pending يُوجَّه لشاشة قيد المراجعة
        if ($user->status === 'pending') {
            return redirect()->route('auth.pending')->with('status', 'email-verified');
        }

        $targetRoute = $user->isProvider() ? 'provider.dashboard' : 'dashboard';
        return redirect()->intended(route($targetRoute, absolute: false).'?verified=1');
    }
}
