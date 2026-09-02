<?php

namespace App\Http\Controllers;

use App\Models\RequestAttempt;
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
        $user = $request->user();
        $tab = $request->query('tab', 'active');
        $search = $request->query('search');

        // استعلام الطلبات الخاصة بالمستخدم
        $query = $user->serviceRequests()
            ->with(['assignedProvider.registrationProfile', 'attempts.provider', 'review'])
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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        $serviceRequest = DB::transaction(function () use ($request, $validated) {
            $publicId = ServiceRequest::generatePublicId();

            $titleLower = mb_strtolower($validated['title']);
            $serviceType = 'grocery';
            if (str_contains($titleLower, 'دواء') || str_contains($titleLower, 'صيدلية')) {
                $serviceType = 'medicine';
            } elseif (str_contains($titleLower, 'مرافقة') || str_contains($titleLower, 'طبي') || str_contains($titleLower, 'مستشفى') || str_contains($titleLower, 'عيادة')) {
                $serviceType = 'medical_escort';
            } elseif (str_contains($titleLower, 'منزل') || str_contains($titleLower, 'تنظيف') || str_contains($titleLower, 'ترتيب')) {
                $serviceType = 'home_help';
            }

            $district = 'حي الرمال';
            $loc = $validated['location'];
            if (str_contains($loc, 'النصر')) {
                $district = 'حي النصر';
            } elseif (str_contains($loc, 'الشيخ رضوان')) {
                $district = 'الشيخ رضوان';
            } elseif (str_contains($loc, 'تل الهوا')) {
                $district = 'تل الهوا';
            } elseif (str_contains($loc, 'دير البلح')) {
                $district = 'دير البلح';
            } elseif (str_contains($loc, 'خانيونس')) {
                $district = 'خانيونس';
            }

            $serviceRequest = $request->user()->serviceRequests()->create([
                'public_id' => $publicId,
                'title' => $validated['title'],
                'service_type' => $serviceType,
                'description' => $validated['description'],
                'location' => $validated['location'],
                'district' => $district,
                'distance_km' => round(mt_rand(12, 45) / 10, 1),
                'scheduled_at' => $validated['scheduled_at'],
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]);

            // تسجيل المحاولة الأولى في سجل المحاولات
            $serviceRequest->attempts()->create([
                'attempt_number' => 1,
                'scheduled_at' => $validated['scheduled_at'],
                'location' => $validated['location'],
                'outcome' => 'pending',
                'notes' => 'المحاولة الأولى لإنشاء ونشر الطلب',
            ]);

            return $serviceRequest;
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-created');
    }

    /**
     * تحديد موعد جديد وإعادة نشر الطلب بنفس الرقم والتاريخ (القاعدة الجوهرية).
     */
    public function reschedule(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
        ]);

        DB::transaction(function () use ($serviceRequest, $validated) {
            $newAttemptNumber = $serviceRequest->attempts_count + 1;

            $serviceRequest->update([
                'scheduled_at' => $validated['scheduled_at'],
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'assigned_provider_id' => null,
                'accepted_at' => null,
                'started_at' => null,
                'attempts_count' => $newAttemptNumber,
            ]);

            $serviceRequest->attempts()->create([
                'attempt_number' => $newAttemptNumber,
                'scheduled_at' => $validated['scheduled_at'],
                'location' => $serviceRequest->location,
                'outcome' => 'pending',
                'notes' => 'تحديد موعد جديد وإعادة نشر الطلب',
            ]);
        });

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

        DB::transaction(function () use ($serviceRequest, $validated) {
            $newAttemptNumber = $serviceRequest->attempts_count + 1;

            $serviceRequest->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'scheduled_at' => $validated['scheduled_at'],
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'assigned_provider_id' => null,
                'accepted_at' => null,
                'started_at' => null,
                'attempts_count' => $newAttemptNumber,
            ]);

            $serviceRequest->attempts()->create([
                'attempt_number' => $newAttemptNumber,
                'scheduled_at' => $validated['scheduled_at'],
                'location' => $validated['location'],
                'outcome' => 'pending',
                'notes' => 'تعديل بيانات الطلب وإعادة النشر',
            ]);
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-updated');
    }

    /**
     * تأكيد كبير السن لاكتمال تنفيذ الخدمة.
     */
    public function confirmCompletion(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return redirect()->route('service-requests.index', ['tab' => 'completed'])
            ->with('status', 'request-completed');
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

        DB::transaction(function () use ($serviceRequest, $validated) {
            $newAttemptNumber = $serviceRequest->attempts_count + 1;
            $scheduledAt = $validated['scheduled_at'] ?? $serviceRequest->scheduled_at;

            $serviceRequest->update([
                'scheduled_at' => $scheduledAt,
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'assigned_provider_id' => null,
                'accepted_at' => null,
                'started_at' => null,
                'attempts_count' => $newAttemptNumber,
            ]);

            $serviceRequest->attempts()->create([
                'attempt_number' => $newAttemptNumber,
                'scheduled_at' => $scheduledAt,
                'location' => $serviceRequest->location,
                'outcome' => 'pending',
                'notes' => 'فك الإسناد والبحث عن مقدم خدمة بديل',
            ]);
        });

        return redirect()->route('service-requests.index', ['tab' => 'active'])
            ->with('status', 'request-reassigned');
    }

    /**
     * إلغاء الطلب وحفظه في قسم الملغاة.
     */
    public function cancel(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeOwner($request, $serviceRequest);

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
                'provider_id' => $serviceRequest->assigned_provider_id ?? $request->user()->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
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
        if ($serviceRequest->user_id !== $request->user()->id) {
            abort(403, 'غير مصرح لك بإجراء هذا التعديل على هذا الطلب.');
        }
    }
}

