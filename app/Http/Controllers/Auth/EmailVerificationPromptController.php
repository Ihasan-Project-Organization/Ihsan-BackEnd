<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            if ($user->status === 'pending') {
                return redirect()->route('auth.pending');
            }

            $targetRoute = $user->isProvider() ? 'provider.dashboard' : 'dashboard';
            return redirect()->intended(route($targetRoute, absolute: false));
        }

        return view('auth.verify-email');
    }
}
