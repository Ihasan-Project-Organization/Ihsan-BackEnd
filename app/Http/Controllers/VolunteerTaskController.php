<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Rating;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VolunteerCertificate;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VolunteerTaskController extends Controller
{
    /**
     * 1. لوحة تحكم مقدم الخدمة (الرئيسية).
     */
    public function dashboard(Request $request): View
    {
        $provider = $request->user();
        $setting = $provider->serviceProviderProfile;

        $providerProfileId = $setting?->id;

        // إحصائيات لوحة التحكم
        $avgRating = $provider->receivedReviews()->avg('stars') ?? 4.8;
        $totalReviews = $provider->receivedReviews()->count();
        $completedCount = ServiceRequest::where('provider_id', $providerProfileId)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count();
        $thisWeekCount = ServiceRequest::where('provider_id', $providerProfileId)
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $availableCount = ServiceRequest::availableForProvider($provider)->count();

        // الطلب القادم الأقرب لمقدم الخدمة
        $nextTask = ServiceRequest::where('provider_id', $providerProfileId)
            ->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ASSIGNED,
                ServiceRequest::STATUS_IN_PROGRESS,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])
            ->orderBy('scheduled_at', 'asc')
            ->first();

        // آخر الطلبات المرتبطة بمقدم الخدمة
        $recentTasks = ServiceRequest::where('provider_id', $providerProfileId)
            ->latest('updated_at')
            ->take(5)
            ->get();

        // آخر الطلبات المتاحة كمعاينة سريعة
        $previewAvailable = ServiceRequest::availableForProvider($provider)
            ->latest()
            ->take(3)
            ->get();

        $tier = (int) ($setting?->tier ?? 1);
        $t2Tasks = (int) \App\Models\SystemSetting::get('tier_2_tasks_threshold', 10);
        $t3Tasks = (int) \App\Models\SystemSetting::get('tier_3_tasks_threshold', 30);
        $nextTierTarget = $tier === 1 ? $t2Tasks : ($tier === 2 ? $t3Tasks : null);
        $tasksToNextTier = $nextTierTarget ? max(0, $nextTierTarget - $completedCount) : 0;
        $tierProgress = $nextTierTarget ? min(100, (int)(($completedCount / $nextTierTarget) * 100)) : 100;

        return view('provider.dashboard', compact(
            'provider',
            'setting',
            'avgRating',
            'totalReviews',
            'completedCount',
            'thisWeekCount',
            'availableCount',
            'nextTask',
            'recentTasks',
            'previewAvailable',
            'tier',
            'tasksToNextTier',
            'tierProgress'
        ));
    }

    /**
     * 2. صفحة الطلبات المتاحة للقبول.
     */
    public function available(Request $request): View
    {
        $provider = $request->user();
        $serviceType = $request->query('service_type');
        $search = $request->query('search');

        $query = ServiceRequest::availableForProvider($provider);

        if ($serviceType && in_array($serviceType, ['grocery', 'medical_escort', 'medicine', 'home_help', 'social_visit', 'support_request'])) {
            $query->where('service_type', $serviceType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('public_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'soonest');

        if ($sort === 'soonest') {
            $query->orderBy('scheduled_at', 'asc');
        } else {
            $query->latest('scheduled_at');
        }

        $requests = $query->paginate(12)
            ->withQueryString();

        $categoryCounts = [
            'all' => ServiceRequest::availableForProvider($provider)->count(),
            'grocery' => ServiceRequest::availableForProvider($provider)->where('service_type', 'grocery')->count(),
            'medical_escort' => ServiceRequest::availableForProvider($provider)->where('service_type', 'medical_escort')->count(),
            'medicine' => ServiceRequest::availableForProvider($provider)->where('service_type', 'medicine')->count(),
            'home_help' => ServiceRequest::availableForProvider($provider)->where('service_type', 'home_help')->count(),
            'social_visit' => ServiceRequest::availableForProvider($provider)->where('service_type', 'social_visit')->count(),
            'support_request' => ServiceRequest::availableForProvider($provider)->where('service_type', 'support_request')->count(),
        ];
        $counts = $categoryCounts;

        return view('provider.available', compact('requests', 'categoryCounts', 'counts', 'serviceType', 'search', 'sort'));
    }

    /**
     * 3. صفحة طلباتي (المسندة والجارية والمنتهية).
     */
    public function myTasks(Request $request): View
    {
        $provider = $request->user();
        $tab = $request->query('tab', 'upcoming');
        $search = $request->query('search');

        $providerProfileId = $provider->serviceProviderProfile?->id;

        $baseQuery = ServiceRequest::where('provider_id', $providerProfileId)
            ->with(['review']);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ASSIGNED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])->count(),
            'in_progress' => (clone $baseQuery)->where('status', ServiceRequest::STATUS_IN_PROGRESS)->count(),
            'pending_confirmation' => (clone $baseQuery)->where('status', ServiceRequest::STATUS_PENDING_CONFIRMATION)->count(),
            'completed' => (clone $baseQuery)->where('status', ServiceRequest::STATUS_COMPLETED)->count(),
        ];

        $query = clone $baseQuery;

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('public_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        match ($tab) {
            'upcoming' => $query->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ASSIGNED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])->orderBy('scheduled_at', 'asc'),
            'in_progress' => $query->where('status', ServiceRequest::STATUS_IN_PROGRESS)->latest('started_at'),
            'pending_confirmation' => $query->where('status', ServiceRequest::STATUS_PENDING_CONFIRMATION)->latest('updated_at'),
            'completed' => $query->where('status', ServiceRequest::STATUS_COMPLETED)->latest('completed_at'),
            'all' => $query->latest('updated_at'),
            default => $query->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ASSIGNED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])->orderBy('scheduled_at', 'asc'),
        };

        $requests = $query->paginate(10)->withQueryString();

        return view('provider.tasks', compact('requests', 'tab', 'counts', 'search'));
    }

    /**
     * 4. صفحة الأداء وسجل التقييمات.
     */
    public function performance(Request $request): View
    {
        $provider = $request->user();
        $providerProfileId = $provider->serviceProviderProfile?->id;

        $reviews = $provider->receivedReviews()
            ->with(['serviceRequest'])
            ->latest()
            ->paginate(10);

        $totalServices = ServiceRequest::where('provider_id', $providerProfileId)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count();

        $fiveStarsCount = $provider->receivedReviews()->where('stars', 5)->count();
        $onTimeCount = $totalServices; // تقريبي
        $apologiesCount = ServiceRequest::where('provider_id', $providerProfileId)
            ->where('status', ServiceRequest::STATUS_PROVIDER_APOLOGIZED)
            ->count();

        $avgRating = $provider->receivedReviews()->avg('stars') ?? 5.0;

        $profile = $provider->serviceProviderProfile;
        $tier = (int) ($profile?->tier ?? 1);
        $t2Tasks = (int) \App\Models\SystemSetting::get('tier_2_tasks_threshold', 10);
        $t3Tasks = (int) \App\Models\SystemSetting::get('tier_3_tasks_threshold', 30);
        $t2Rating = (float) \App\Models\SystemSetting::get('tier_2_rating_threshold', 4.0);
        $t3Rating = (float) \App\Models\SystemSetting::get('tier_3_rating_threshold', 4.3);
        $nextTierTarget = $tier === 1 ? $t2Tasks : ($tier === 2 ? $t3Tasks : null);
        $nextTierRatingTarget = $tier === 1 ? $t2Rating : ($tier === 2 ? $t3Rating : null);
        $tasksToNextTier = $nextTierTarget ? max(0, $nextTierTarget - $totalServices) : 0;
        $tierProgress = $nextTierTarget ? min(100, (int)(($totalServices / $nextTierTarget) * 100)) : 100;

        return view('provider.performance', compact(
            'provider',
            'profile',
            'reviews',
            'totalServices',
            'fiveStarsCount',
            'onTimeCount',
            'apologiesCount',
            'avgRating',
            'tier',
            'nextTierTarget',
            'nextTierRatingTarget',
            'tasksToNextTier',
            'tierProgress'
        ));
    }

    /**
     * 5. صفحة شهادات التطوع الرقمية.
     */
    public function certificates(Request $request): View
    {
        $provider = $request->user();
        $profile = $provider->serviceProviderProfile;

        $completedCount = $profile ? ServiceRequest::where('provider_id', $profile->id)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count() : 0;

        $certificates = $profile ? $profile->volunteerCertificates()->latest()->get() : collect();

        // الساعات التقديرية (ساعة واحدة لكل مهمة مكتملة وفق المرجع)
        $estimatedHours = $completedCount * 1;

        return view('provider.certificates', compact(
            'provider',
            'profile',
            'completedCount',
            'estimatedHours',
            'certificates'
        ));
    }

    /**
     * طلب إصدار شهادة تطوع رقمية جديدة.
     */
    public function requestCertificate(Request $request): RedirectResponse
    {
        $provider = $request->user();
        $profile = $provider->serviceProviderProfile;

        if (! $profile) {
            return back()->with('error', 'الملف الشخصي لمقدم الخدمة غير مكتمل.');
        }

        $completedCount = ServiceRequest::where('provider_id', $profile->id)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count();

        if ($completedCount < 1) {
            return back()->with('error', 'يجب إكمال خدمة تطوعية واحدة على الأقل لإصدار شهادة التطوع.');
        }

        // توليد رقم تسلسلي فريد للشهادة
        $nextNum = ($profile->volunteerCertificates()->count() + 1);
        $certNumber = 'CERT-' . strtoupper(substr(md5($profile->id . '_' . time()), 0, 6)) . '-' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);

        $profile->volunteerCertificates()->create([
            'certificate_number' => $certNumber,
            'issued_at' => now(),
        ]);

        return redirect()->route('provider.certificates')
            ->with('status', 'certificate-issued');
    }

    /**
     * 6. إعدادات التوفر والتشغيل.
     */
    public function availability(Request $request): View
    {
        $provider = $request->user();
        $setting = $provider->serviceProviderProfile;

        return view('provider.availability', compact('provider', 'setting'));
    }

    /**
     * حفظ إعدادات التوفر.
     */
    public function updateAvailability(Request $request): RedirectResponse
    {
        $provider = $request->user();
        $profile = $provider->serviceProviderProfile;

        if ($profile) {
            $profile->update([
                'is_available' => $request->boolean('is_available'),
            ]);
        }

        return redirect()->route('provider.availability')
            ->with('status', 'settings-updated');
    }

    /**
     * قبول الطلب الفوري مع فحص الصلاحيات والقفل المتفائل (Pessimistic Lock).
     */
    public function accept(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $provider = $request->user();

        $providerProfile = $provider->serviceProviderProfile()->first() ?? ServiceProviderProfile::create([
            'user_id' => $provider->id,
            'full_name' => $provider->name,
            'birth_date' => '1995-01-01',
            'id_document_path' => 'documents/id.png',
            'good_conduct_cert_path' => 'documents/conduct.pdf',
        ]);

        // 1. فحص التطابق لمستوى مقدم الخدمة (Tier Eligibility)
        $providerTier = (int) ($providerProfile->tier ?? 1);
        $requiredTier = match ($serviceRequest->service_type) {
            'medical_escort' => 3,
            'home_help' => 2,
            default => 1,
        };

        if ($providerTier < $requiredTier) {
            abort(403, 'هذا الطلب غير متاح لمستوى حسابك الحالي.');
        }

        // 2. فحص تطابق تفضيل الجنس (Gender Preference Matching)
        $providerGender = $provider->gender ?? $providerProfile->gender ?? $request->input('gender') ?? null;
        if ($serviceRequest->gender_preference && $serviceRequest->gender_preference !== 'any') {
            if ($providerGender && $providerGender !== $serviceRequest->gender_preference) {
                abort(403, 'هذا الطلب غير متاح لك (تفضيل الجنس غير متطابق).');
            }
        }

        return DB::transaction(function () use ($serviceRequest, $providerProfile) {
            $requestLocked = ServiceRequest::where('id', $serviceRequest->id)
                ->lockForUpdate()
                ->first();

            if ($requestLocked->status !== ServiceRequest::STATUS_PENDING_ACCEPTANCE || $requestLocked->provider_id !== null) {
                return redirect()->route('provider.available')
                    ->with('error', 'أُسند لغيرك: لقد قام مقدم خدمة آخر بقبول هذا الطلب أولاً.');
            }

            $requestLocked->update([
                'provider_id' => $providerProfile->id,
                'status' => ServiceRequest::STATUS_ASSIGNED,
                'accepted_at' => now(),
                'assigned_at' => now(),
            ]);

            return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
                ->with('status', 'task-accepted');
        });
    }

    /**
     * تجاوز الطلب وإخفاؤه.
     */
    public function dismiss(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        return redirect()->route('provider.available')
            ->with('status', 'task-dismissed');
    }

    /**
     * بدء تقديم الخدمة فعلياً (الحالة: قيد التنفيذ).
     */
    public function startService(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'in_progress'])
            ->with('status', 'service-started');
    }

    /**
     * إنهاء الخدمة (الحالة: بانتظار التأكيد).
     */
    public function finishService(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'pending_confirmation'])
            ->with('status', 'service-finished');
    }

    /**
     * الإبلاغ عن تأخير متوقع.
     */
    public function reportDelay(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $request->validate([
            'delay_minutes' => ['required', 'integer', 'min:5', 'max:60'],
            'delay_reason' => ['required', 'string', 'max:500'],
        ]);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_PROVIDER_DELAYED,
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'delay-reported');
    }

    /**
     * الاعتذار عن الطلب وفصل الإسناد مع زيادة عداد الموثوقية وإشعار كبير السن.
     */
    public function apologize(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        if (! $serviceRequest->canBeApologized()) {
            return back()->withErrors(['apology' => 'لا يمكن الاعتذار عن هذا الطلب في حالته الحالية.']);
        }

        $request->validate([
            'apology_reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($serviceRequest, $request) {
            $providerProfile = $request->user()->serviceProviderProfile;
            if ($providerProfile) {
                $providerProfile->recordReliabilityIncident('apology', $serviceRequest->id);
            }

            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'incident_type' => 'apology',
                'previous_provider_id' => $providerProfile?->id,
                'provider_id' => null,
                'accepted_at' => null,
                'assigned_at' => null,
                'started_at' => null,
            ]);

            $elderUserId = $serviceRequest->elderProfile?->user_id;
            if ($elderUserId) {
                Notification::create([
                    'user_id' => $elderUserId,
                    'type' => 'provider_apologized',
                    'message' => "اعتذر مقدم الخدمة عن تنفيذ الطلب {$serviceRequest->public_id}، وتمت إعادة طرح الطلب فوراً للبحث عن متطوع آخر.",
                ]);
            }
        });

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'apology-completed');
    }

    /**
     * تقييم اختياري لكبير السن من قبل مقدم الخدمة.
     */
    public function rateElder(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $providerProfileId = $request->user()->serviceProviderProfile?->id;
        if (! $providerProfileId) {
            abort(403, 'غير مصرح.');
        }

        if (! in_array($serviceRequest->status, [ServiceRequest::STATUS_COMPLETED, ServiceRequest::STATUS_PENDING_CONFIRMATION], true)) {
            return back()->withErrors(['rate' => 'يمكن التقييم فقط للطلبات المنجزة أو بانتظار التأكيد.']);
        }

        $validated = $request->validate([
            'stars' => ['nullable', 'integer', 'between:1,5'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $stars = (int) ($validated['stars'] ?? $validated['rating'] ?? 5);

        Rating::updateOrCreate(
            [
                'service_request_id' => $serviceRequest->id,
                'rater_role' => 'provider',
            ],
            [
                'elderly_id' => $serviceRequest->elderProfile?->user_id ?? $serviceRequest->elder_id,
                'provider_id' => $request->user()->id,
                'stars' => $stars,
                'comment' => $validated['comment'] ?? null,
                'visible_to_provider' => false, // لا يظهر لأي مقدم خدمة آخر، فقط للإدارة
            ]
        );

        return back()->with('status', 'elder-rated');
    }

    /**
     * التحقق من أن مقدم الخدمة هو المسند إليه الطلب الحالي.
     */
    private function authorizeProvider(Request $request, ServiceRequest $serviceRequest): void
    {
        $providerProfileId = $request->user()->serviceProviderProfile?->id;

        if (!$providerProfileId || $serviceRequest->provider_id !== $providerProfileId) {
            abort(403, 'غير مصرح لك بإجراء أي عملية على هذا الطلب.');
        }
    }
}
