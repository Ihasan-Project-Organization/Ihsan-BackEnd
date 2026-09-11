<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    /**
     * عرض قائمة المستخدمين مع البحث والفلترة حسب الدور والحالة.
     */
    public function index(Request $request)
    {
        $query = User::with(['elderProfile', 'serviceProviderProfile', 'admin'])
            ->latest('created_at');

        // فلتر البحث بالاسم أو البريد
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        // فلتر الدور
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'elder') {
                $query->whereHas('elderProfile')->whereDoesntHave('serviceProviderProfile')->whereDoesntHave('admin');
            } elseif ($role === 'provider') {
                $query->whereHas('serviceProviderProfile');
            } elseif ($role === 'admin') {
                $query->whereHas('admin');
            }
        }

        // فلتر الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15)->withQueryString();

        // إحصائيات سريعة للبطاقات
        $stats = [
            'total'     => User::count(),
            'approved'  => User::where('status', 'approved')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'pending'   => User::where('status', 'pending')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * عرض ملف مستخدم مفصل وسجل الشكاوى والحوادث المرتبطة به.
     */
    public function show(User $user)
    {
        $user->load([
            'elderProfile',
            'serviceProviderProfile',
            'admin',
            'notifications' => fn ($q) => $q->latest()->limit(8),
        ]);

        $requestsCount = 0;
        $completedRequestsCount = 0;
        $complaintsAgainst = collect();

        if ($user->elderProfile) {
            $requestsCount = ServiceRequest::where('elder_id', $user->elderProfile->id)->count();
            $completedRequestsCount = ServiceRequest::where('elder_id', $user->elderProfile->id)
                ->where('status', ServiceRequest::STATUS_COMPLETED)
                ->count();

            // شكاوى مرفوعة على طلبات كبير السن
            $complaintsAgainst = Complaint::whereHas('serviceRequest', fn ($q) => $q->where('elder_id', $user->elderProfile->id))
                ->with(['reporter', 'serviceRequest'])
                ->latest()
                ->get();
        } elseif ($user->serviceProviderProfile) {
            $requestsCount = ServiceRequest::where('provider_id', $user->serviceProviderProfile->id)->count();
            $completedRequestsCount = ServiceRequest::where('provider_id', $user->serviceProviderProfile->id)
                ->where('status', ServiceRequest::STATUS_COMPLETED)
                ->count();

            // شكاوى مرفوعة على طلبات مقدم الخدمة
            $complaintsAgainst = Complaint::whereHas('serviceRequest', fn ($q) => $q->where('provider_id', $user->serviceProviderProfile->id))
                ->with(['reporter', 'serviceRequest'])
                ->latest()
                ->get();
        }

        // الشكاوى التي قدمها المستخدم بنفسه
        $complaintsSubmitted = $user->complaints()->with('serviceRequest')->latest()->get();

        // سجل حوادث عدم الموثوقية (إذا كان مقدم خدمة)
        $reliabilityIncidents = $user->serviceProviderProfile
            ? $user->serviceProviderProfile->reliabilityIncidents()->with('request')->latest()->get()
            : collect();

        return view('admin.users.show', compact(
            'user',
            'requestsCount',
            'completedRequestsCount',
            'complaintsAgainst',
            'complaintsSubmitted',
            'reliabilityIncidents'
        ));
    }

    /**
     * إيقاف حساب مستخدم مع سبب إلزامي.
     */
    public function suspend(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك إيقاف حسابك الإداري الخاص.');
        }

        $request->validate([
            'suspension_reason' => ['required', 'string', 'max:1000'],
        ], [
            'suspension_reason.required' => 'حقل سبب الإيقاف إلزامي لتأكيد قرار التعليق.',
        ]);

        $user->update([
            'status'            => 'suspended',
            'suspension_reason' => $request->suspension_reason,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'account_suspended',
            'message' => "تم إيقاف حسابك من قِبل إدارة المنصة. السبب: {$request->suspension_reason}",
        ]);

        \App\Models\AdminAuditLog::log(
            'suspended_user',
            'User',
            $user->id,
            $request->suspension_reason,
            ['user_name' => $user->name, 'email' => $user->email]
        );

        return back()->with('success', "تم إيقاف حساب ({$user->name}) بنجاح، ومُنِع من دخول المنصة.");
    }

    /**
     * إعادة تفعيل حساب مستخدم موقوف.
     */
    public function reactivate(Request $request, User $user)
    {
        $user->update([
            'status'            => 'approved',
            'suspension_reason' => null,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => 'account_reactivated',
            'message' => 'تمت إعادة تفعيل حسابك بنجاح من قِبل إدارة منصة إحسان. يمكنك الآن تسجيل الدخول واستخدام كافة الخدمات.',
        ]);

        \App\Models\AdminAuditLog::log(
            'reactivated_user',
            'User',
            $user->id,
            'تمت إعادة تفعيل الحساب وإلغاء الإيقاف بنجاح.',
            ['user_name' => $user->name, 'email' => $user->email]
        );

        return back()->with('success', "تمت إعادة تفعيل حساب ({$user->name}) بنجاح.");
    }
}
