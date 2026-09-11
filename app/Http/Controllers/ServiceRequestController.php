<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\ServiceRequest;
use App\Models\ServiceReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    /**
     * عرض صفحة طلباتي بتبويباتها المختلفة.
     */
    public function index(Request $request): View
    {
        // معالجة المواعيد المنتهية تلقائياً
        ServiceRequest::processScheduleExpirations();

        $user = $request->user();
        $tab = $request->query('tab', 'active');
        $search = $request->query('search');

        // استعلام الطلبات الخاصة بالمستخدم
        $query = $user->serviceRequests()
            ->with(['assignedProvider', 'review'])
            ->latest('updated_at');

        // حساب أعداد الطلبات لكل تبويب
        $counts = [
            'all' => (clone $query)->count(),
            'active' => (clone $query)->active()->count(),
            'needs_action' => (clone $query)->needsAction()->count(),
            'completed' => (clone $query)->completed()->count(),
            'cancelled' => (clone $query)->cancelled()->count(),
        ];

        // تطبيق فلتر البحث
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('public_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // تطبيق فلتر التبويب
        match ($tab) {
            'active' => $query->active(),
            'needs_action' => $query->needsAction(),
            'completed' => $query->completed(),
            'cancelled' => $query->cancelled(),
            'all' => null,
            default => $query->active(),
        };

        $requests = $query->paginate(15)->withQueryString();

        return view('service-requests.index', compact('requests', 'tab', 'counts', 'search'));
    }

    /**
     * إنشاء طلب مساعدة جديد.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->filled('location') && $request->filled('location_text')) {
            $request->merge(['location' => $request->input('location_text')]);
        }

        $timingTypeInput = $request->input('timing_type', 'scheduled');
        if ($timingTypeInput === 'immediate' && !$request->filled('scheduled_at')) {
            $request->merge(['scheduled_at' => now()->addMinutes(30)->format('Y-m-d H:i:s')]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'service_type' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'location_text' => ['nullable', 'string', 'max:255'],
            'timing_type' => ['nullable', 'string', 'in:immediate,scheduled'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'gender_preference' => ['nullable', 'string', 'in:male,female,any'],
            'pricing_type' => ['nullable', 'string', 'in:volunteer,paid'],
            'proposed_price' => ['nullable', 'numeric', 'min:0'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,doc,docx', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $publicId = ServiceRequest::generatePublicId();

            $serviceType = $validated['service_type'] ?? null;
            if (!$serviceType || !in_array($serviceType, ['grocery', 'medicine', 'medical_escort', 'home_help', 'social_visit', 'support_request', 'other'])) {
                $titleLower = mb_strtolower($validated['title']);
                $serviceType = 'grocery';
                if (str_contains($titleLower, 'دواء') || str_contains($titleLower, 'صيدلية')) {
                    $serviceType = 'medicine';
                } elseif (str_contains($titleLower, 'مرافقة') || str_contains($titleLower, 'طبي') || str_contains($titleLower, 'مستشفى') || str_contains($titleLower, 'عيادة')) {
                    $serviceType = 'medical_escort';
                } elseif (str_contains($titleLower, 'منزل') || str_contains($titleLower, 'تنظيف') || str_contains($titleLower, 'ترتيب')) {
                    $serviceType = 'home_help';
                } elseif (str_contains($titleLower, 'زيارة') || str_contains($titleLower, 'مؤانسة')) {
                    $serviceType = 'social_visit';
                }
            }

            $timingType = $validated['timing_type'] ?? 'scheduled';
            $genderPreference = $validated['gender_preference'] ?? 'any';
            $pricingType = $validated['pricing_type'] ?? 'volunteer';
            $proposedPrice = ($pricingType === 'paid') ? ($validated['proposed_price'] ?? null) : null;

            $elderProfile = $request->user()->elderProfile ?? \App\Models\ElderProfile::create([
                'user_id' => $request->user()->id,
                'full_name' => $request->user()->name,
                'city' => 'مدينة غزة',
            ]);

            $serviceRequest = $elderProfile->serviceRequests()->create([
                'public_id' => $publicId,
                'title' => $validated['title'],
                'service_type' => $serviceType,
                'pricing_type' => $pricingType,
                'proposed_price' => $proposedPrice,
                'timing_type' => $timingType,
                'gender_preference' => $genderPreference,
                'description' => $validated['description'],
                'location' => $validated['location'],
                'scheduled_at' => $validated['scheduled_at'],
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('request_attachments', 'public');
                        $serviceRequest->attachments()->create([
                            'file_path' => $path,
                        ]);
                    }
                }
            }

            return $serviceRequest;
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-created');
    }

    /**
     * تحديد موعد جديد وإعادة نشر الطلب بنفس الرقم والتاريخ.
     */
    public function reschedule(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $serviceRequest->update([
            'scheduled_at' => $validated['scheduled_at'],
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            'provider_id' => null,
            'accepted_at' => null,
            'started_at' => null,
        ]);

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-rescheduled');
    }

    /**
     * تعديل بيانات الطلب وإعادة نشره.
     */
    public function update(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $serviceRequest->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            'provider_id' => null,
            'accepted_at' => null,
            'started_at' => null,
        ]);

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-updated');
    }

    /**
     * تأكيد كبير السن لاكتمال تنفيذ الخدمة مع التقييم الإجباري.
     */
    public function confirmCompletion(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        if (! in_array($serviceRequest->status, [ServiceRequest::STATUS_PENDING_CONFIRMATION, ServiceRequest::STATUS_COMPLETED], true)) {
            return back()->withErrors(['confirm' => 'لا يمكن تأكيد اكتمال طلب ليس بحالة بانتظار التأكيد.']);
        }

        $validated = $request->validate([
            'stars' => ['nullable', 'integer', 'between:1,5'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $stars = (int) ($validated['stars'] ?? $validated['rating'] ?? 0);
        if ($stars < 1 || $stars > 5) {
            return back()->withErrors(['rating' => 'يرجى اختيار تقييم بالنجوم (1-5) لتأكيد اكتمال الخدمة.']);
        }

        DB::transaction(function () use ($serviceRequest, $request, $stars, $validated) {
            $isFirstCompletion = $serviceRequest->status !== ServiceRequest::STATUS_COMPLETED;

            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_COMPLETED,
                'completed_at' => $serviceRequest->completed_at ?? now(),
            ]);

            $providerUserId = $serviceRequest->provider?->user_id
                ?? $serviceRequest->assignedProvider?->user_id
                ?? $serviceRequest->serviceProviderProfile?->user_id;

            Rating::updateOrCreate(
                [
                    'service_request_id' => $serviceRequest->id,
                    'rater_role' => 'elder',
                ],
                [
                    'elderly_id' => $request->user()->id,
                    'provider_id' => $providerUserId ?? $request->user()->id,
                    'stars' => $stars,
                    'comment' => $validated['comment'] ?? null,
                    'visible_to_provider' => true,
                ]
            );

            $providerProfile = $serviceRequest->serviceProviderProfile;
            if ($providerProfile) {
                if ($isFirstCompletion) {
                    $providerProfile->increment('completed_tasks_count');
                }

                $avg = Rating::where('provider_id', $providerProfile->user_id)
                    ->where('rater_role', 'elder')
                    ->avg('stars');

                $providerProfile->update([
                    'average_rating' => round($avg ?? $stars, 1),
                ]);

                $providerProfile->updateTier();
            }
        });

        return redirect()->route('service-requests.index', ['tab' => 'completed'])
            ->with('status', 'request-completed');
    }

    /**
     * الإبلاغ عن مشكلة وتحويل الطلب للمراجعة (بدلاً من تأكيد الإكمال).
     */
    public function reportProblem(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $validated = $request->validate([
            'problem_description' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $description = $validated['problem_description'] ?? $validated['description'];
        if (empty($description)) {
            return back()->withErrors(['problem_description' => 'يرجى كتابة تفاصيل المشكلة التي واجهتك.']);
        }

        DB::transaction(function () use ($serviceRequest, $request, $description) {
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_UNDER_REVIEW,
            ]);

            Complaint::create([
                'request_id' => $serviceRequest->id,
                'reporter_id' => $request->user()->id,
                'description' => $description,
                'status' => 'open',
            ]);

            Notification::create([
                'user_id' => $request->user()->id,
                'type' => 'problem_reported',
                'message' => "تم استلام بلاغك بشأن الطلب {$serviceRequest->public_id} وإحالته للمراجعة الإدارية.",
            ]);
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'problem-reported');
    }

    /**
     * فك الإسناد والبحث عن مقدم خدمة بديل عند التأخر.
     */
    public function searchAlternative(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $validated = $request->validate([
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ]);

        $scheduledAt = $validated['scheduled_at'] ?? $serviceRequest->scheduled_at;

        DB::transaction(function () use ($serviceRequest, $scheduledAt) {
            $providerProfile = $serviceRequest->serviceProviderProfile;
            if ($providerProfile) {
                $providerProfile->recordReliabilityIncident('delay', $serviceRequest->id);

                $providerUserId = $providerProfile->user_id;
                if ($providerUserId) {
                    Notification::create([
                        'user_id' => $providerUserId,
                        'type' => 'provider_replaced_due_to_delay',
                        'message' => "تم اختيار البحث عن بديل للطلب {$serviceRequest->public_id} بسبب التأخر وتم تسجيل حادثة تأخر.",
                    ]);
                }
            }

            $serviceRequest->update([
                'scheduled_at' => $scheduledAt,
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'incident_type' => 'delay',
                'provider_id' => null,
                'accepted_at' => null,
                'assigned_at' => null,
                'started_at' => null,
            ]);
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-reassigned');
    }

    /**
     * إلغاء الطلب مع تطبيق سياسة الإلغاء الصارمة.
     */
    public function cancel(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        // سياسة الإلغاء الصارمة: ممنوع في assigned, in_progress, pending_confirmation
        if (! $serviceRequest->canBeCancelledByElderly()) {
            abort(403, 'غير مسموح بإلغاء الطلب في حالته الحالية بعد توكيله لمقدم الخدمة أو أثناء تنفيذه.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason'] ?? 'لم تعد الخدمة مطلوبة',
        ]);

        return redirect()->route('service-requests.index', ['tab' => 'cancelled'])
            ->with('status', 'request-cancelled');
    }

    /**
     * إضافة تقييم للخدمة المكتملة.
     */
    public function storeReview(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        if ($serviceRequest->status !== ServiceRequest::STATUS_COMPLETED) {
            return back()->withErrors(['review' => 'لا يمكن تقييم طلب غير مكتمل.']);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $serviceRequest->review()->updateOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'elderly_id' => $request->user()->id,
                'provider_id' => $serviceRequest->provider?->user_id ?? $request->user()->id,
                'rater_role' => 'elder',
                'stars' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'visible_to_provider' => true,
            ]
        );

        return redirect()->route('service-requests.index', ['tab' => 'completed'])
            ->with('status', 'review-submitted');
    }

    /**
     * التحقق من ملكية الطلب لكبير السن المسجل.
     */
    private function authorizeOwner(Request $request, ServiceRequest $serviceRequest): void
    {
        $isOwner = ($serviceRequest->elder_id === $request->user()->elderProfile?->id)
            || ($serviceRequest->elderProfile?->user_id === $request->user()->id);

        if (!$isOwner) {
            abort(403, 'غير مصرح لك بإجراء هذا التعديل على هذا الطلب.');
        }
    }
}
