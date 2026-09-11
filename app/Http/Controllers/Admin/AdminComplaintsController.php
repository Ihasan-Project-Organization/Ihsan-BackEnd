<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ServiceProviderProfile;
use Illuminate\Http\Request;

class AdminComplaintsController extends Controller
{
    /**
     * قائمة الشكاوى والبلاغات + تبويب تنبيهات الموثوقية.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'complaints');

        // 1. بيانات تبويب الشكاوى
        $complaintsQuery = Complaint::with(['reporter', 'serviceRequest.elderProfile.user', 'serviceRequest.serviceProviderProfile.user'])
            ->latest('created_at');

        if ($request->filled('status')) {
            $complaintsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $complaintsQuery->whereHas('reporter', fn ($q) => $q->where('name', 'like', "%{$s}%"));
        }

        $complaints = $complaintsQuery->paginate(15)->withQueryString();

        // 2. بيانات تبويب تنبيهات الموثوقية (3 حوادث فأكثر في آخر 30 يوماً)
        $flaggedProviders = ServiceProviderProfile::with([
            'user',
            'reliabilityIncidents' => function ($q) {
                $q->where('created_at', '>=', now()->subDays(30))->latest('created_at');
            },
            'reliabilityIncidents.request'
        ])
        ->whereHas('reliabilityIncidents', function ($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        }, '>=', 3)
        ->get();

        // إحصائيات التبويبات
        $openComplaintsCount    = Complaint::where('status', 'open')->count();
        $reliabilityAlertsCount = $flaggedProviders->count();

        return view('admin.complaints.index', compact(
            'complaints',
            'flaggedProviders',
            'tab',
            'openComplaintsCount',
            'reliabilityAlertsCount'
        ));
    }

    /**
     * تفاصيل الشكوى مع تفاصيل الطلب ونموذج الحل الإداري.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load([
            'reporter',
            'serviceRequest.elderProfile.user',
            'serviceRequest.serviceProviderProfile.user',
        ]);

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * تحديث حالة الشكوى وحفظ ملاحظات الإدارة أو إغلاقها.
     */
    public function resolve(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status'      => ['required', 'in:open,under_review,closed'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $complaint->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        \App\Models\AdminAuditLog::log(
            'resolved_complaint',
            'Complaint',
            $complaint->id,
            $request->admin_notes ?? "تحديث حالة الشكوى إلى ({$request->status})",
            [
                'complaint_id' => $complaint->id,
                'status'       => $request->status,
                'admin_notes'  => $request->admin_notes,
            ]
        );

        $statusText = match ($request->status) {
            'closed'       => 'تم إغلاق الشكوى بنجاح.',
            'under_review' => 'تم وضع الشكوى قيد المراجعة.',
            default        => 'تم تحديث الشكوى بنجاح.',
        };

        return back()->with('success', $statusText);
    }
}
