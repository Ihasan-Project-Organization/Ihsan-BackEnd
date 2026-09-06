<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('frontend.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // 1. فحص تأكيد البريد الإلكتروني أولاً:
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // 2. فحص حالة الاعتماد الإداري ثانياً:
        if ($user->status === 'pending') {
            return redirect()->route('auth.pending');
        }

        // 3. توجيه المستخدم حسب دوره (Role):
        if ($user->isProvider()) {
            return redirect()->intended(route('provider.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
