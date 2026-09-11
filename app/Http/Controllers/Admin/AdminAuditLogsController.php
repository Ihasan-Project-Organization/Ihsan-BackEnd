<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogsController extends Controller
{
    /**
     * استعراض سجل العمليات والتدقيق الإداري (Audit Log).
     */
    public function index(Request $request): View
    {
        $query = AdminAuditLog::with(['admin.admin'])->latest();

        // 1. بحث بالكلمات المفتاحية في السبب، أو نوع الإجراء، أو اسم/بريد المدير
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('reason', 'like', "%{$s}%")
                  ->orWhere('action', 'like', "%{$s}%")
                  ->orWhere('target_type', 'like', "%{$s}%")
                  ->orWhereHas('admin', function ($adminQ) use ($s) {
                      $adminQ->where('name', 'like', "%{$s}%")
                             ->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        // 2. فلتر نوع الإجراء
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 3. فلتر المدير المنفذ
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // 4. فلتر التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // إحصائيات سريعة للبطاقات
        $stats = [
            'total'       => AdminAuditLog::count(),
            'today'       => AdminAuditLog::whereDate('created_at', today())->count(),
            'suspensions' => AdminAuditLog::where('action', 'suspended_user')->count(),
            'approvals'   => AdminAuditLog::where('action', 'approved_user')->count(),
        ];

        $logs = $query->paginate(20)->withQueryString();

        // قائمة المديرين للفلترة
        $adminsList = User::whereHas('admin')->get();

        // قائمة أنواع الإجراءات المعتمدة
        $actionsList = [
            'approved_user'          => 'اعتماد حساب',
            'rejected_user'          => 'رفض طلب حساب',
            'resubmission_requested' => 'طلب استكمال مستندات',
            'suspended_user'         => 'إيقاف حساب مستخدم',
            'reactivated_user'       => 'إعادة تفعيل حساب',
            'force_status_changed'   => 'تغيير حالة طلب إدارياً',
            'resolved_complaint'     => 'معالجة / إغلاق شكوى',
            'created_admin'          => 'إضافة مدير نظام جديد',
            'deleted_admin'          => 'حذف مدير نظام',
            'updated_settings'       => 'تحديث إعدادات النظام',
        ];

        return view('admin.audit_logs.index', compact(
            'logs',
            'stats',
            'adminsList',
            'actionsList'
        ));
    }
}
