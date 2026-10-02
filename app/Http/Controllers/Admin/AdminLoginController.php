<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * عرض صفحة تسجيل دخول الأدمن.
     */
    public function create(): View
    {
        return view('admin.login');
    }

    /**
     * معالجة طلب تسجيل دخول الأدمن.
     * يتحقق من صحة البيانات ومن أن المستخدم فعلاً أدمن.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'بيانات تسجيل الدخول غير صحيحة.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        // التأكد أن المستخدم أدمن فعلاً
        if (! $user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'هذا الحساب ليس حساب إدارة. استخدم صفحة تسجيل الدخول العادية.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * تسجيل خروج الأدمن.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
