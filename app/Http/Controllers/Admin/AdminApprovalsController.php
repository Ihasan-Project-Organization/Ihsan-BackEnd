<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminApprovalsController extends Controller
{
    /**
     * عرض قائمة الحسابات بانتظار الاعتماد.
     */
    public function index(Request $request)
    {
        $query = User::where('status', 'pending')
            ->with(['elderProfile', 'serviceProviderProfile'])
            ->latest();

        // بحث بالاسم أو البريد
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        // فلتر الدور
        if ($request->filled('role')) {
            if ($request->role === 'elder') {
                $query->whereHas('elderProfile');
            } elseif ($request->role === 'provider') {
                $query->whereHas('serviceProviderProfile');
            }
        }

        $pendingUsers = $query->paginate(15)->withQueryString();

        return view('admin.approvals.index', compact('pendingUsers'));
    }

    /**
     * عرض تفاصيل حساب معلق ومستنداته المرفوعة.
     */
    public function show(User $user)
    {
        $user->load(['elderProfile', 'serviceProviderProfile']);

        return view('admin.approvals.show', compact('user'));
    }

    /**
     * إجراء 1: اعتماد الحساب.
     */
    public function approve(Request $request, User $user)
    {
        $user->update([
            'status'            => 'approved',
            'rejection_reason'  => null,
            'resubmission_note' => null,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'account_approved',
            'message' => 'تم اعتماد حسابك بنجاح من قِبل إدارة منصة إحسان. يمكنك الآن الاستفادة من كافة خدمات المنصة.',
        ]);

        \App\Models\AdminAuditLog::log(
            'approved_user',
            'User',
            $user->id,
            'تم اعتماد الحساب وتفعيله بنجاح.',
            ['user_name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('admin.approvals.index')->with('success', "تم اعتماد حساب ({$user->name}) بنجاح.");
    }

    /**
     * إجراء 2: رفض الحساب مع سبب إلزامي.
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'حقل سبب الرفض إلزامي.',
        ]);

        $user->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'account_rejected',
            'message' => "تم رفض طلب اعتماد حسابك من قِبل الإدارة. السبب: {$request->rejection_reason}",
        ]);

        \App\Models\AdminAuditLog::log(
            'rejected_user',
            'User',
            $user->id,
            $request->rejection_reason,
            ['user_name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('admin.approvals.index')->with('success', "تم رفض طلب اعتماد حساب ({$user->name}).");
    }

    /**
     * إجراء 3: طلب استكمال مستندات مع إشعار بنص الملاحظة والحالة تبقى pending.
     */
    public function requestResubmission(Request $request, User $user)
    {
        $request->validate([
            'resubmission_note' => ['required', 'string', 'max:1000'],
        ], [
            'resubmission_note.required' => 'حقل الملاحظات المطلوب استكمالها إلزامي.',
        ]);

        $user->update([
            'resubmission_note' => $request->resubmission_note,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'documents_resubmission_requested',
            'message' => "طلب استكمال مستندات من إدارة المنصة: {$request->resubmission_note}",
        ]);

        \App\Models\AdminAuditLog::log(
            'resubmission_requested',
            'User',
            $user->id,
            $request->resubmission_note,
            ['user_name' => $user->name, 'email' => $user->email]
        );

        return back()->with('success', "تم إرسال طلب استكمال المستندات إلى ({$user->name}) بنجاح، ويبقى الحساب بانتظار الاعتماد.");
    }

    /**
     * عرض المستندات الحساسة المحمية (بطاقات الهوية وشهادات حسن السيرة).
     */
    public function viewDocument(string $path)
    {
        // حماية ضد هجمات مسار المجلدات (Directory Traversal)
        $cleanPath = ltrim(str_replace(['../', '..\\'], '', $path), '/');

        if (Storage::disk('local')->exists($cleanPath)) {
            return Storage::disk('local')->response($cleanPath);
        }

        // دعم توافقي للمستندات السابقة المرفوعة على القرص العام أثناء التطوير
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->response($cleanPath);
        }

        abort(404, 'المستند غير موجود.');
    }
}
