<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->rejection_reason
                    ? "السبب: {$user->rejection_reason}"
                    : 'يرجى التواصل مع إدارة المنصة للمزيد من التفاصيل.';

                return redirect()->route('login')->withErrors([
                    'email' => "تم رفض حسابك من قِبل الإدارة. {$reason}",
                ]);
            }

            if ($user->status === 'suspended') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->suspension_reason
                    ? "السبب: {$user->suspension_reason}"
                    : 'يرجى التواصل مع إدارة المنصة للمزيد من التفاصيل.';

                return redirect()->route('login')->withErrors([
                    'email' => "تم إيقاف حسابك من قِبل إدارة المنصة. {$reason}",
                ]);
            }

            // 1. فحص تأكيد البريد الإلكتروني أولاً:
            // يجب توثيق البريد أولاً قبل أي فحص لحالة الاعتماد الإداري
            if (! $user->hasVerifiedEmail()) {
                if ($request->routeIs('verification.*') || $request->routeIs('logout')) {
                    return $next($request);
                }

                return redirect()->route('verification.notice');
            }

            // 2. فحص حالة الاعتماد الإداري ثانياً (بعد التأكد التام من توثيق البريد):
            if ($user->status === 'pending') {
                // اسمح فقط لشاشة قيد المراجعة وتسجيل الخروج ومسارات الإدارة للمديرين
                if (! $request->routeIs('auth.pending') && ! $request->routeIs('logout') && ! ($user->admin && $request->routeIs('admin.*'))) {
                    return redirect()->route('auth.pending');
                }
            }
        }

        return $next($request);
    }
}
