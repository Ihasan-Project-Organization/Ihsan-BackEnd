<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAdminsController extends Controller
{
    /**
     * استعراض قائمة كافة مديري النظام.
     */
    public function index(): View
    {
        $admins = Admin::with('user')->latest()->get();
        $currentAdminUserId = Auth::id();

        $stats = [
            'total'       => $admins->count(),
            'super_admin' => $admins->where('admin_level', 'super_admin')->count(),
            'regular'     => $admins->where('admin_level', 'admin')->count(),
        ];

        return view('admin.admins.index', compact('admins', 'currentAdminUserId', 'stats'));
    }

    /**
     * شاشة إضافة مدير جديد.
     */
    public function create(): View
    {
        return view('admin.admins.create');
    }

    /**
     * حفظ مدير نظام جديد (إنشاء حساب مستخدم وسجل إداري).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'admin_level' => ['required', 'in:admin,super_admin'],
        ], [
            'name.required'        => 'حقل اسم المدير مطلوب.',
            'email.required'       => 'حقل البريد الإلكتروني مطلوب.',
            'email.email'          => 'يرجى إدخال عنوان بريد إلكتروني صحيح.',
            'email.unique'         => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required'    => 'كلمة المرور مطلوبة.',
            'password.min'         => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
            'password.confirmed'   => 'تأكيد كلمة المرور غير متطابق.',
            'admin_level.required' => 'يرجى اختيار مستوى صلاحية المدير.',
            'admin_level.in'       => 'مستوى الصلاحية المحدد غير صالح.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'status'            => 'approved',
                'email_verified_at' => now(),
            ]);

            $adminRecord = Admin::create([
                'user_id'     => $user->id,
                'admin_level' => $validated['admin_level'],
            ]);

            \App\Models\AdminAuditLog::log(
                'created_admin',
                'Admin',
                $adminRecord->id,
                "تم تعيين مدير نظام جديد ({$user->name}) بمستوى ({$validated['admin_level']})",
                [
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'admin_level' => $validated['admin_level'],
                ]
            );

            return $user;
        });

        return redirect()->route('admin.admins.index')
            ->with('success', "تم إضافة المدير ({$user->name}) وتفعيل حسابه بنجاح.");
    }

    /**
     * حذف حساب مدير نظام.
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        // منع المدير الحالي من حذف نفسه
        if ($admin->user_id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص كمدير لنظام إحسان.');
        }

        $adminName = $admin->user?->name ?? 'المدير';

        DB::transaction(function () use ($admin, $adminName) {
            $adminEmail = $admin->user?->email;
            $adminId = $admin->id;
            $user = $admin->user;

            $admin->delete();

            if ($user) {
                $user->delete();
            }

            \App\Models\AdminAuditLog::log(
                'deleted_admin',
                'Admin',
                $adminId,
                "تم حذف حساب المدير ({$adminName})",
                [
                    'name'  => $adminName,
                    'email' => $adminEmail,
                ]
            );
        });

        return redirect()->route('admin.admins.index')
            ->with('success', "تم حذف حساب المدير ({$adminName}) بنجاح.");
    }
}
