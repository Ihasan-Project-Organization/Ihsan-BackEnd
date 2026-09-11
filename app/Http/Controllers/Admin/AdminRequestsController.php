<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class AdminRequestsController extends Controller
{
    /**
     * قائمة الطلبات مع الفلترة والبحث.
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['elderProfile.user', 'serviceProviderProfile.user'])
            ->latest('created_at');

        // البحث بالنص أو المعرف العام أو أسماء الأطراف
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('public_id', 'like', "%{$s}%")
                  ->orWhereHas('elderProfile.user', fn ($uq) => $uq->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('serviceProviderProfile.user', fn ($uq) => $uq->where('name', 'like', "%{$s}%"));
            });
        }

        // الفلترة بالحالة
        if ($request->filled('status')) {
            $status = $request->status;

            if ($status === 'active') {
                $query->whereIn('status', [
                    ServiceRequest::STATUS_ACCEPTED,
                    ServiceRequest::STATUS_ASSIGNED,
                    ServiceRequest::STATUS_IN_PROGRESS,
                ]);
            } elseif ($status === 'needs_action') {
                $query->whereIn('status', [
                    ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                    ServiceRequest::STATUS_PROVIDER_DELAYED,
                    ServiceRequest::STATUS_NO_PROVIDER_FOUND,
                ]);
            } else {
                $query->where('status', $status);
            }
        }

        $requests = $query->paginate(15)->withQueryString();
        $statuses = ServiceRequest::STATUSES;

        // إحصائيات سريعة للتبويبات
        $counts = [
            'all'          => ServiceRequest::count(),
            'active'       => ServiceRequest::whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ASSIGNED,
                ServiceRequest::STATUS_IN_PROGRESS,
            ])->count(),
            'needs_action' => ServiceRequest::whereIn('status', [
                ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
                ServiceRequest::STATUS_NO_PROVIDER_FOUND,
            ])->count(),
            'completed'    => ServiceRequest::where('status', ServiceRequest::STATUS_COMPLETED)->count(),
            'under_review' => ServiceRequest::where('status', ServiceRequest::STATUS_UNDER_REVIEW)->count(),
        ];

        return view('admin.requests.index', compact('requests', 'statuses', 'counts'));
    }

    /**
     * تفاصيل الطلب مع السجل والخط الزمني لتغيرات الحالة.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'elderProfile.user',
            'serviceProviderProfile.user',
            'complaints.reporter',
            'ratings',
        ]);

        // بناء الخط الزمني للطلب بالاعتماد على الحقول الزمنية الفعلية
        $timeline = [];

        // 1. الإنشاء
        if ($serviceRequest->created_at) {
            $timeline[] = [
                'title'       => 'تم إنشاء الطلب',
                'description' => 'تم إنشاء ونشر الطلب بواسطة كبير السن (' . ($serviceRequest->elderProfile?->user?->name ?? 'المستفيد') . ')',
                'time'        => $serviceRequest->created_at,
                'status'      => 'completed',
                'icon'        => 'fa-solid fa-file-circle-plus',
                'color'       => '#3b82f6',
            ];
        }

        // 2. القبول
        if ($serviceRequest->accepted_at) {
            $timeline[] = [
                'title'       => 'تم قبول الطلب مبدئياً',
                'description' => 'تم قبول الطلب من قبل مقدم الخدمة (' . ($serviceRequest->serviceProviderProfile?->user?->name ?? 'المتطوع') . ')',
                'time'        => $serviceRequest->accepted_at,
                'status'      => 'completed',
                'icon'        => 'fa-solid fa-user-check',
                'color'       => '#8b5cf6',
            ];
        }

        // 3. التوكيل والإسناد الرسمي
        if ($serviceRequest->assigned_at) {
            $timeline[] = [
                'title'       => 'تم إسناد المهمة رسمياً',
                'description' => 'تم تأكيد التوكيل وانتقال الطلب لمرحلة الاستعداد للتنفيذ',
                'time'        => $serviceRequest->assigned_at,
                'status'      => 'completed',
                'icon'        => 'fa-solid fa-handshake',
                'color'       => '#0ea5e9',
            ];
        }

        // 4. بدء التنفيذ
        if ($serviceRequest->started_at) {
            $timeline[] = [
                'title'       => 'بدء تنفيذ الخدمة ميدانياً',
                'description' => 'باشر المتطوع تقديم الخدمة في الموقع المحدد',
                'time'        => $serviceRequest->started_at,
                'status'      => 'completed',
                'icon'        => 'fa-solid fa-person-running',
                'color'       => '#f59e0b',
            ];
        }

        // 5. الاكتمال
        if ($serviceRequest->completed_at) {
            $timeline[] = [
                'title'       => 'اكتملت الخدمة بنجاح',
                'description' => 'تم إنجاز المهمة وتأكيد الانتهاء وتقييم الخدمة',
                'time'        => $serviceRequest->completed_at,
                'status'      => 'completed',
                'icon'        => 'fa-solid fa-circle-check',
                'color'       => '#10b981',
            ];
        }

        // 6. الإلغاء إن وُجد
        if ($serviceRequest->cancelled_at) {
            $reason = $serviceRequest->cancellation_reason ? " (السبب: {$serviceRequest->cancellation_reason})" : '';
            $timeline[] = [
                'title'       => 'تم إلغاء الطلب',
                'description' => 'تم تسجيل إلغاء الطلب' . $reason,
                'time'        => $serviceRequest->cancelled_at,
                'status'      => 'cancelled',
                'icon'        => 'fa-solid fa-circle-xmark',
                'color'       => '#ef4444',
            ];
        }

        // ترتيب الخط الزمني تصاعدياً حسب الوقت
        usort($timeline, fn ($a, $b) => $a['time']->timestamp <=> $b['time']->timestamp);

        return view('admin.requests.show', compact('serviceRequest', 'timeline'));
    }

    /**
     * تدخل إداري اختياري لتغيير حالة الطلب.
     */
    public function forceStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', ServiceRequest::STATUSES)],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update(['status' => $request->status]);

        \App\Models\AdminAuditLog::log(
            'force_status_changed',
            'ServiceRequest',
            $serviceRequest->id,
            $request->reason ?? "تغيير حالة الطلب من ({$oldStatus}) إلى ({$request->status})",
            [
                'public_id'   => $serviceRequest->public_id,
                'old_status'  => $oldStatus,
                'new_status'  => $request->status,
                'reason'      => $request->reason,
            ]
        );

        return back()->with('success', "تم تحديث حالة الطلب إدارياً من ({$oldStatus}) إلى ({$request->status}) بنجاح.");
    }
}
