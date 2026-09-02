<?php

namespace App\Http\Controllers;

use App\Models\ProviderDismissedRequest;
use App\Models\ProviderSetting;
use App\Models\RequestAttempt;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VolunteerTaskController extends Controller
{
    /**
     * 1. لوحة المتابعة والوصول إلى الطلبات (Dashboard مقدم الخدمة).
     */
    public function dashboard(Request $request): View
    {
        $provider = $request->user();
        $setting = $provider->getOrCreateProviderSetting();

        // إحصائيات لوحة التحكم
        $avgRating = $provider->receivedReviews()->avg('rating') ?? 4.8;
        $totalReviews = $provider->receivedReviews()->count();
        $completedCount = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count();
        $thisWeekCount = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $availableCount = ServiceRequest::availableForProvider($provider)->count();

        // الطلب القادم الأقرب لمقدم الخدمة
        $nextTask = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ON_THE_WAY,
                ServiceRequest::STATUS_ARRIVED,
                ServiceRequest::STATUS_IN_PROGRESS,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])
            ->orderBy('scheduled_at', 'asc')
            ->with('user.registrationProfile')
            ->first();

        // آخر الطلبات المرتبطة بمقدم الخدمة
        $recentTasks = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        // آخر الطلبات المتاحة كمعاينة سريعة
        $previewAvailable = ServiceRequest::availableForProvider($provider)
            ->latest()
            ->take(3)
            ->get();

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
            'previewAvailable'
        ));
    }

    /**
     * 2. عرض الطلبات المتاحة واتخاذ القرار (القبول أو التجاوز).
     */
    public function available(Request $request): View
    {
        $provider = $request->user();
        $serviceType = $request->query('service_type');
        $district = $request->query('district');
        $sort = $request->query('sort', 'nearest');
        $search = $request->query('search');

        $query = ServiceRequest::availableForProvider($provider)
            ->with('user.registrationProfile');

        // أعداد الفئات
        $categoryCounts = [
            'all' => (clone $query)->count(),
            'grocery' => (clone $query)->where('service_type', 'grocery')->count(),
            'medical_escort' => (clone $query)->where('service_type', 'medical_escort')->count(),
            'medicine' => (clone $query)->where('service_type', 'medicine')->count(),
            'home_help' => (clone $query)->where('service_type', 'home_help')->count(),
        ];

        // تطبيق الفلاتر
        if ($serviceType && $serviceType !== 'all') {
            $query->where('service_type', $serviceType);
        }

        if ($district && $district !== 'all') {
            $query->where('district', $district);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('public_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // الترتيب: الأقرب أولاً أو الأقرب بالموعد
        if ($sort === 'nearest') {
            $query->orderBy('distance_km', 'asc');
        } elseif ($sort === 'soonest') {
            $query->orderBy('scheduled_at', 'asc');
        } else {
            $query->latest();
        }

        $requests = $query->paginate(12)->withQueryString();

        return view('provider.available', compact(
            'requests',
            'serviceType',
            'district',
            'sort',
            'search',
            'categoryCounts'
        ));
    }

    /**
     * 3. شاشة طلباتي وإدارة المهام (القادمة، قيد التنفيذ، بانتظار التأكيد، المكتملة).
     */
    public function myTasks(Request $request): View
    {
        $provider = $request->user();
        $tab = $request->query('tab', 'upcoming');
        $search = $request->query('search');

        $baseQuery = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->with(['user.registrationProfile', 'review', 'attempts']);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ON_THE_WAY,
                ServiceRequest::STATUS_ARRIVED,
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
                ServiceRequest::STATUS_ON_THE_WAY,
                ServiceRequest::STATUS_ARRIVED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])->orderBy('scheduled_at', 'asc'),
            'in_progress' => $query->where('status', ServiceRequest::STATUS_IN_PROGRESS)->latest('started_at'),
            'pending_confirmation' => $query->where('status', ServiceRequest::STATUS_PENDING_CONFIRMATION)->latest('updated_at'),
            'completed' => $query->where('status', ServiceRequest::STATUS_COMPLETED)->latest('completed_at'),
            'all' => $query->latest('updated_at'),
            default => $query->whereIn('status', [
                ServiceRequest::STATUS_ACCEPTED,
                ServiceRequest::STATUS_ON_THE_WAY,
                ServiceRequest::STATUS_ARRIVED,
                ServiceRequest::STATUS_PROVIDER_DELAYED,
            ])->orderBy('scheduled_at', 'asc'),
        };

        $requests = $query->paginate(10)->withQueryString();

        return view('provider.tasks', compact('requests', 'tab', 'counts', 'search'));
    }

    /**
     * 4. صفحة الأداء، الالتزام وسجل التقييمات.
     */
    public function performance(Request $request): View
    {
        $provider = $request->user();
        $setting = $provider->getOrCreateProviderSetting();

        $reviews = $provider->receivedReviews()
            ->with(['elderly.registrationProfile', 'serviceRequest'])
            ->latest()
            ->paginate(10);

        $totalServices = ServiceRequest::where('assigned_provider_id', $provider->id)
            ->where('status', ServiceRequest::STATUS_COMPLETED)
            ->count();

        $fiveStarsCount = $provider->receivedReviews()->where('rating', 5)->count();
        $onTimeCount = max(0, $totalServices - 2);
        $apologiesCount = RequestAttempt::where('provider_id', $provider->id)
            ->where('outcome', 'provider_apologized')
            ->count();

        $avgRating = $provider->receivedReviews()->avg('rating') ?? 4.8;

        return view('provider.performance', compact(
            'provider',
            'setting',
            'reviews',
            'totalServices',
            'fiveStarsCount',
            'onTimeCount',
            'apologiesCount',
            'avgRating'
        ));
    }

    /**
     * 5. إعدادات التوفر والتشغيل وقاموس الحالات.
     */
    public function availability(Request $request): View
    {
        $provider = $request->user();
        $setting = $provider->getOrCreateProviderSetting();

        return view('provider.availability', compact('provider', 'setting'));
    }

    /**
     * حفظ إعدادات التوفر والأيام وساعات العمل.
     */
    public function updateAvailability(Request $request): RedirectResponse
    {
        $provider = $request->user();
        $setting = $provider->getOrCreateProviderSetting();

        $validated = $request->validate([
            'available_days' => ['nullable', 'array'],
            'available_days.*' => ['string', 'in:sat,sun,mon,tue,wed,thu,fri'],
            'available_from' => ['required', 'string'],
            'available_to' => ['required', 'string'],
            'is_available' => ['required', 'boolean'],
            'offered_services' => ['nullable', 'array'],
            'offered_services.*' => ['string', 'in:grocery,medical_escort,medicine,home_help'],
            'service_city' => ['required', 'string', 'max:100'],
            'coverage_radius_km' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $setting->update([
            'available_days' => $validated['available_days'] ?? ['sat', 'sun', 'mon', 'tue', 'wed', 'thu'],
            'available_from' => $validated['available_from'],
            'available_to' => $validated['available_to'],
            'is_available' => (bool) $validated['is_available'],
            'offered_services' => $validated['offered_services'] ?? ['grocery', 'medical_escort', 'medicine', 'home_help'],
            'service_city' => $validated['service_city'],
            'coverage_radius_km' => $validated['coverage_radius_km'],
        ]);

        return redirect()->route('provider.availability')->with('status', 'settings-updated');
    }

    /**
     * قبول الطلب وإسناده حصرياً لمقدم الخدمة (صفحة 6 و 8).
     */
    public function accept(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $provider = $request->user();

        return DB::transaction(function () use ($provider, $serviceRequest) {
            // قفل السجل للتأكد من عدم قبول الطلب من متطوع آخر في نفس اللحظة
            $requestLocked = ServiceRequest::where('id', $serviceRequest->id)->lockForUpdate()->first();

            if ($requestLocked->status !== ServiceRequest::STATUS_PENDING_ACCEPTANCE || $requestLocked->assigned_provider_id !== null) {
                return redirect()->route('provider.available')
                    ->with('error', 'أُسند لغيرك: لقد قام مقدم خدمة آخر بقبول هذا الطلب أولاً.');
            }

            $requestLocked->update([
                'assigned_provider_id' => $provider->id,
                'status' => ServiceRequest::STATUS_ACCEPTED,
                'accepted_at' => now(),
            ]);

            $requestLocked->attempts()->create([
                'attempt_number' => $requestLocked->attempts_count,
                'provider_id' => $provider->id,
                'scheduled_at' => $requestLocked->scheduled_at,
                'location' => $requestLocked->location,
                'outcome' => 'accepted',
                'notes' => 'تم قبول الطلب وإسناده حصرياً لمقدم الخدمة: ' . $provider->name,
            ]);

            return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
                ->with('status', 'task-accepted');
        });
    }

    /**
     * تجاوز الطلب وإخفاؤه دون التأثير على التقييم أو الالتزام (صفحة 6).
     */
    public function dismiss(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $provider = $request->user();

        ProviderDismissedRequest::firstOrCreate([
            'user_id' => $provider->id,
            'service_request_id' => $serviceRequest->id,
        ]);

        return redirect()->route('provider.available')
            ->with('status', 'task-dismissed');
    }

    /**
     * بدء التوجه إلى الموقع (الحالة: في الطريق - صفحة 10).
     */
    public function startHeading(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_ON_THE_WAY,
            'on_the_way_at' => now(),
        ]);

        $serviceRequest->attempts()->create([
            'attempt_number' => $serviceRequest->attempts_count,
            'provider_id' => $request->user()->id,
            'scheduled_at' => $serviceRequest->scheduled_at,
            'location' => $serviceRequest->location,
            'outcome' => 'on_the_way',
            'notes' => 'بدأ مقدم الخدمة التوجه إلى موقع المستفيد في تمام ' . now()->format('H:i'),
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'heading-started');
    }

    /**
     * تسجيل وتأكيد الوصول إلى الموقع (الحالة: وصل - صفحة 10).
     */
    public function confirmArrival(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_ARRIVED,
            'arrived_at' => now(),
        ]);

        $serviceRequest->attempts()->create([
            'attempt_number' => $serviceRequest->attempts_count,
            'provider_id' => $request->user()->id,
            'scheduled_at' => $serviceRequest->scheduled_at,
            'location' => $serviceRequest->location,
            'outcome' => 'arrived',
            'notes' => 'سجّل مقدم الخدمة وصوله إلى الموقع بنجاح في تمام ' . now()->format('H:i'),
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'arrival-confirmed');
    }

    /**
     * بدء تقديم الخدمة فعلياً (الحالة: قيد التنفيذ - صفحة 13).
     */
    public function startService(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        $serviceRequest->attempts()->create([
            'attempt_number' => $serviceRequest->attempts_count,
            'provider_id' => $request->user()->id,
            'scheduled_at' => $serviceRequest->scheduled_at,
            'location' => $serviceRequest->location,
            'outcome' => 'in_progress',
            'notes' => 'تم بدء تنفيذ الخدمة في تمام ' . now()->format('H:i'),
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'in_progress'])
            ->with('status', 'service-started');
    }

    /**
     * إنهاء الخدمة وإرسال ملخص التنفيذ (الحالة: بانتظار التأكيد - صفحة 13).
     */
    public function finishService(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $validated = $request->validate([
            'completion_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
            'completion_notes' => $validated['completion_notes'] ?? 'تم إكمال تقديم الخدمة ومساعدة كبير السن بنجاح.',
        ]);

        $serviceRequest->attempts()->create([
            'attempt_number' => $serviceRequest->attempts_count,
            'provider_id' => $request->user()->id,
            'scheduled_at' => $serviceRequest->scheduled_at,
            'location' => $serviceRequest->location,
            'outcome' => 'pending_confirmation',
            'notes' => 'أنهى مقدم الخدمة المهمة وأرسل إشعار الإكمال للمستفيد. الملاحظات: ' . ($validated['completion_notes'] ?? 'لا يوجد'),
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'pending_confirmation'])
            ->with('status', 'service-finished');
    }

    /**
     * الإبلاغ عن تأخير متوقع وحساب مؤشر الالتزام (مسار استثنائي - صفحة 11 و 12).
     */
    public function reportDelay(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        $validated = $request->validate([
            'delay_minutes' => ['required', 'integer', 'min:5', 'max:60'],
            'delay_reason' => ['required', 'string', 'max:500'],
        ]);

        $minutes = (int) $validated['delay_minutes'];
        $reason = $validated['delay_reason'];

        // خصم نقاط الالتزام حسب جدول صفحة 11 و 12:
        // أقل من 15 دقيقة: دون خصم (0)
        // 15 - 30 دقيقة: خصم نقطتين (-2)
        // أكثر من 30 دقيقة: خصم 5 نقاط (-5)
        $penalty = 0;
        if ($minutes >= 15 && $minutes <= 30) {
            $penalty = 2;
        } elseif ($minutes > 30) {
            $penalty = 5;
        }

        if ($penalty > 0) {
            $setting = $request->user()->getOrCreateProviderSetting();
            $setting->decrement('commitment_score', $penalty);
        }

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_PROVIDER_DELAYED,
            'delay_reported_at' => now(),
            'expected_arrival_at' => now()->addMinutes($minutes),
            'delay_reason' => $reason,
        ]);

        $serviceRequest->attempts()->create([
            'attempt_number' => $serviceRequest->attempts_count,
            'provider_id' => $request->user()->id,
            'scheduled_at' => $serviceRequest->scheduled_at,
            'location' => $serviceRequest->location,
            'outcome' => 'provider_delayed',
            'notes' => "تم الإبلاغ عن تأخير لمدة {$minutes} دقيقة بسبب: {$reason}. (خصم {$penalty} نقاط التزام)",
        ]);

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'delay-reported');
    }

    /**
     * الاعتذار عن الطلب وفصل الإسناد مع حساب تأثير مؤشر الالتزام (صفحة 16 و 17).
     */
    public function apologize(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeProvider($request, $serviceRequest);

        if (!$serviceRequest->canBeApologized()) {
            return back()->withErrors(['apology' => 'لا يمكن الاعتذار عن هذا الطلب في حالته الحالية.']);
        }

        $validated = $request->validate([
            'apology_reason' => ['required', 'string', 'max:500'],
        ]);

        $reason = $validated['apology_reason'];
        $now = now();
        $scheduledAt = $serviceRequest->scheduled_at;
        $hoursRemaining = $now->diffInHours($scheduledAt, false);

        // حساب خصم نقاط الالتزام حسب الوثيقة (صفحة 17):
        // أكثر من 24 ساعة: خصم نقطتين (2)
        // خلال 24 ساعة (أقل من 24 وأكثر من ساعتين): خصم 5 نقاط (5)
        // قبل ساعتين أو أثناء التنفيذ: خصم 10 نقاط (10)
        $penalty = 2;
        if ($hoursRemaining <= 2) {
            $penalty = 10;
        } elseif ($hoursRemaining <= 24) {
            $penalty = 5;
        }

        DB::transaction(function () use ($request, $serviceRequest, $reason, $penalty) {
            $setting = $request->user()->getOrCreateProviderSetting();
            if ($setting->commitment_score >= $penalty) {
                $setting->decrement('commitment_score', $penalty);
            } else {
                $setting->update(['commitment_score' => 0]);
            }

            // فك الإسناد ورفع رقم المحاولة وإعادة نشر الطلب
            $newAttemptNumber = $serviceRequest->attempts_count + 1;

            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                'assigned_provider_id' => null,
                'accepted_at' => null,
                'on_the_way_at' => null,
                'arrived_at' => null,
                'started_at' => null,
                'attempts_count' => $newAttemptNumber,
            ]);

            $serviceRequest->attempts()->create([
                'attempt_number' => $newAttemptNumber,
                'provider_id' => $request->user()->id,
                'scheduled_at' => $serviceRequest->scheduled_at,
                'location' => $serviceRequest->location,
                'outcome' => 'provider_apologized',
                'notes' => "اعتذر مقدم الخدمة عن الطلب: {$reason}. تم خصم {$penalty} نقاط التزام.",
            ]);
        });

        return redirect()->route('provider.tasks', ['tab' => 'upcoming'])
            ->with('status', 'apology-completed');
    }

    /**
     * التحقق من أن مقدم الخدمة هو المسند إليه الطلب الحالي.
     */
    private function authorizeProvider(Request $request, ServiceRequest $serviceRequest): void
    {
        if ($serviceRequest->assigned_provider_id !== $request->user()->id) {
            abort(403, 'غير مصرح لك بإجراء أي عملية على هذا الطلب.');
        }
    }
}

