<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * لوحة التحكم الرئيسية مع بطاقات الإحصاءات السبع وروابطها المباشرة.
     */
    public function index()
    {
        // 1. عدد كبار السن
        $eldersCount = ElderProfile::count();

        // 2. عدد مقدمي الخدمة
        $providersCount = ServiceProviderProfile::count();

        // 3. عدد الحسابات بانتظار الاعتماد
        $pendingAccountsCount = User::where('status', 'pending')->count();

        // 4. عدد الطلبات النشطة
        $activeRequestsCount = ServiceRequest::whereIn('status', [
            ServiceRequest::STATUS_ACCEPTED,
            ServiceRequest::STATUS_ASSIGNED,
            ServiceRequest::STATUS_IN_PROGRESS,
        ])->count();

        // 5. عدد الطلبات "بحاجة لإجراء"
        $needsActionRequestsCount = ServiceRequest::whereIn('status', [
            'provider_apologized',
            'provider_delayed',
            'no_provider_found',
        ])->count();

        // 6. عدد البلاغات المفتوحة
        $openComplaintsCount = Complaint::where('status', 'open')->count();

        // 7. عدد مقدمي الخدمة الذين وصلوا لـ 3 حوادث موثوقية خلال آخر 30 يوماً
        $reliabilityAlertsCount = ServiceProviderProfile::whereHas('reliabilityIncidents', function ($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        }, '>=', 3)->count();

        // آخر طلبات الاعتماد المعلقة
        $recentPendingUsers = User::with(['elderProfile', 'serviceProviderProfile'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // آخر الطلبات
        $recentRequests = ServiceRequest::with(['elderProfile.user', 'serviceProviderProfile.user'])
            ->latest()
            ->limit(6)
            ->get();

        // آخر الشكاوى
        $recentComplaints = Complaint::with(['reporter', 'serviceRequest'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'eldersCount',
            'providersCount',
            'pendingAccountsCount',
            'activeRequestsCount',
            'needsActionRequestsCount',
            'openComplaintsCount',
            'reliabilityAlertsCount',
            'recentPendingUsers',
            'recentRequests',
            'recentComplaints'
        ));
    }
}
