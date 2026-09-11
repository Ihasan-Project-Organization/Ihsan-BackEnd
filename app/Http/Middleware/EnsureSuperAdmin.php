<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            abort(403, 'غير مخوّل.');
        }

        $admin = Auth::user()->admin;

        if (! $admin || $admin->admin_level !== 'super_admin') {
            abort(403, 'هذا القسم مخصص لمدير النظام الأعلى (Super Admin) فقط.');
        }

        return $next($request);
    }
}
