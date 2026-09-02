<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    use HasFactory;

    // الحالات التشغيلية الكاملة للطلب
    public const STATUS_PENDING_ACCEPTANCE = 'pending_acceptance'; // طلب متاح
    public const STATUS_ACCEPTED = 'accepted';                     // تم القبول / إسناد للمقدم
    public const STATUS_ON_THE_WAY = 'on_the_way';                 // في الطريق
    public const STATUS_ARRIVED = 'arrived';                       // وصل إلى الموقع
    public const STATUS_IN_PROGRESS = 'in_progress';               // قيد التنفيذ
    public const STATUS_PENDING_CONFIRMATION = 'pending_confirmation'; // بانتظار التأكيد
    public const STATUS_COMPLETED = 'completed';                   // مكتمل
    public const STATUS_UNDER_REVIEW = 'under_review';             // تحت المراجعة (اعتراض / مشكلة)
    public const STATUS_NO_SHOW = 'no_show';                       // عدم حضور
    public const STATUS_NO_PROVIDER_FOUND = 'no_provider_found';   // لم يتم العثور على مقدم خدمة
    public const STATUS_PROVIDER_APOLOGIZED = 'provider_apologized'; // اعتذر مقدم الخدمة
    public const STATUS_PROVIDER_DELAYED = 'provider_delayed';     // مقدم الخدمة متأخر
    public const STATUS_CANCELLED = 'cancelled';                   // تم إلغاء الطلب

    protected $fillable = [
        'public_id',
        'user_id',
        'assigned_provider_id',
        'title',
        'service_type',
        'description',
        'location',
        'district',
        'distance_km',
        'scheduled_at',
        'status',
        'attempts_count',
        'accepted_at',
        'on_the_way_at',
        'arrived_at',
        'started_at',
        'completed_at',
        'delay_reported_at',
        'expected_arrival_at',
        'delay_reason',
        'completion_notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'on_the_way_at' => 'datetime',
        'arrived_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delay_reported_at' => 'datetime',
        'expected_arrival_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'attempts_count' => 'integer',
        'distance_km' => 'float',
    ];

    /**
     * كبير السن صاحب الطلب (المستفيد).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * مقدم الخدمة المسند إليه الطلب (المتطوع).
     */
    public function assignedProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_provider_id');
    }

    /**
     * سجل محاولات الطلب وتاريخ التغييرات.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(RequestAttempt::class, 'service_request_id')->orderBy('attempt_number', 'desc');
    }

    /**
     * تقييم الخدمة للطلب المكتمل.
     */
    public function review(): HasOne
    {
        return $this->hasOne(ServiceReview::class, 'service_request_id');
    }

    /**
     * الطلبات المتجاوزة.
     */
    public function dismissals(): HasMany
    {
        return $this->hasMany(ProviderDismissedRequest::class, 'service_request_id');
    }

    /**
     * نطاق الطلبات النشطة من منظور كبير السن.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING_ACCEPTANCE,
            self::STATUS_ACCEPTED,
            self::STATUS_ON_THE_WAY,
            self::STATUS_ARRIVED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PENDING_CONFIRMATION,
        ]);
    }

    /**
     * نطاق الطلبات التي بحاجة إلى إجراء من كبير السن.
     */
    public function scopeNeedsAction(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_NO_PROVIDER_FOUND,
            self::STATUS_PROVIDER_APOLOGIZED,
            self::STATUS_PROVIDER_DELAYED,
            self::STATUS_UNDER_REVIEW,
        ]);
    }

    /**
     * نطاق الطلبات المكتملة.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * نطاق الطلبات الملغاة.
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_CANCELLED, self::STATUS_NO_SHOW]);
    }

    /**
     * نطاق الطلبات المتاحة لمتطوع معين (لم يقبلها أحد، ولم يتجاوزها).
     */
    public function scopeAvailableForProvider(Builder $query, User $provider): Builder
    {
        $dismissedIds = ProviderDismissedRequest::where('user_id', $provider->id)->pluck('service_request_id');

        return $query->where('status', self::STATUS_PENDING_ACCEPTANCE)
            ->whereNull('assigned_provider_id')
            ->whereNotIn('id', $dismissedIds)
            ->where('user_id', '!=', $provider->id);
    }

    /**
     * نطاق طلبات المتطوع القادمة (تم القبول أو في الطريق أو وصل).
     */
    public function scopeProviderUpcoming(Builder $query, int $providerId): Builder
    {
        return $query->where('assigned_provider_id', $providerId)
            ->whereIn('status', [
                self::STATUS_ACCEPTED,
                self::STATUS_ON_THE_WAY,
                self::STATUS_ARRIVED,
            ]);
    }

    /**
     * نطاق طلبات المتطوع قيد التنفيذ حالياً.
     */
    public function scopeProviderInProgress(Builder $query, int $providerId): Builder
    {
        return $query->where('assigned_provider_id', $providerId)
            ->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * نطاق طلبات المتطوع بانتظار تأكيد المستفيد.
     */
    public function scopeProviderPendingConfirmation(Builder $query, int $providerId): Builder
    {
        return $query->where('assigned_provider_id', $providerId)
            ->where('status', self::STATUS_PENDING_CONFIRMATION);
    }

    /**
     * نطاق طلبات المتطوع المكتملة.
     */
    public function scopeProviderCompleted(Builder $query, int $providerId): Builder
    {
        return $query->where('assigned_provider_id', $providerId)
            ->where('status', self::STATUS_COMPLETED);
    }

    /**
     * توليد معرف تسلسلي فريد للطلب بصيغة REQ-1001.
     */
    public static function generatePublicId(): string
    {
        $last = self::max('id') ?? 0;
        $next = 1040 + $last + 1;
        return '#REQ-' . $next;
    }

    /**
     * الترتيب الرقمي للخطوة الحالية في مسار الطلب (من 1 إلى 7).
     */
    public function getStepIndexAttribute(): int
    {
        return match ($this->status) {
            self::STATUS_PENDING_ACCEPTANCE => 1,
            self::STATUS_ACCEPTED => 2,
            self::STATUS_ON_THE_WAY => 3,
            self::STATUS_ARRIVED => 4,
            self::STATUS_IN_PROGRESS => 5,
            self::STATUS_PENDING_CONFIRMATION => 6,
            self::STATUS_COMPLETED => 7,
            default => 2,
        };
    }

    /**
     * هل يمكن لمقدم الخدمة الاعتذار عن الطلب الآن؟
     * يتاح الاعتذار في حالات: تم القبول، في الطريق، وصل، قيد التنفيذ (قبل الضغط على إنهاء الخدمة).
     */
    public function canBeApologized(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_ON_THE_WAY,
            self::STATUS_ARRIVED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PROVIDER_DELAYED,
        ], true);
    }

    /**
     * هل يمكن للمستفيد إلغاء الطلب مباشرة؟
     * يتاح الإلغاء فقط عندما لا يكون هناك مقدم خدمة مرتبط ويعمل بصورة طبيعية.
     */
    public function canBeCancelledByElderly(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_ACCEPTANCE,
            self::STATUS_NO_PROVIDER_FOUND,
            self::STATUS_PROVIDER_APOLOGIZED,
            self::STATUS_PROVIDER_DELAYED,
        ], true);
    }

    /**
     * اسم نوع الخدمة معرباً.
     */
    public function getServiceTypeLabelAttribute(): string
    {
        return match ($this->service_type) {
            'grocery' => 'شراء أغراض منزلية',
            'medical_escort' => 'مرافقة إلى موعد طبي',
            'medicine' => 'إحضار دواء',
            'home_help' => 'مساعدة منزلية خفيفة',
            default => $this->title ?? 'خدمة عامة',
        };
    }

    /**
     * أيقونة نوع الخدمة.
     */
    public function getServiceTypeIconAttribute(): string
    {
        return match ($this->service_type) {
            'grocery' => '🛒',
            'medical_escort' => '🚶‍♂️',
            'medicine' => '💊',
            'home_help' => '🧹',
            default => '🤝',
        };
    }

    /**
     * اسم التبويب التابع له الطلب (لكبير السن).
     */
    public function getTabAttribute(): string
    {
        if (
            in_array($this->status, [
                self::STATUS_PENDING_ACCEPTANCE,
                self::STATUS_ACCEPTED,
                self::STATUS_ON_THE_WAY,
                self::STATUS_ARRIVED,
                self::STATUS_IN_PROGRESS,
                self::STATUS_PENDING_CONFIRMATION,
            ])
        ) {
            return 'active';
        }

        if (
            in_array($this->status, [
                self::STATUS_NO_PROVIDER_FOUND,
                self::STATUS_PROVIDER_APOLOGIZED,
                self::STATUS_PROVIDER_DELAYED,
                self::STATUS_UNDER_REVIEW,
            ])
        ) {
            return 'needs_action';
        }

        if ($this->status === self::STATUS_COMPLETED) {
            return 'completed';
        }

        return 'cancelled';
    }

    /**
     * النص المعرب لحالة الطلب.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_ACCEPTANCE => 'طلب متاح (بانتظار مقدم خدمة)',
            self::STATUS_ACCEPTED => 'تم القبول (مسند للمقدم)',
            self::STATUS_ON_THE_WAY => 'في الطريق للموقع',
            self::STATUS_ARRIVED => 'وصل إلى الموقع',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_PENDING_CONFIRMATION => 'بانتظار تأكيد المستفيد',
            self::STATUS_COMPLETED => 'مكتمل وتم التقييم',
            self::STATUS_UNDER_REVIEW => 'تحت المراجعة والاعتراض',
            self::STATUS_NO_SHOW => 'عدم حضور',
            self::STATUS_NO_PROVIDER_FOUND => 'لم يتم العثور على مقدم خدمة',
            self::STATUS_PROVIDER_APOLOGIZED => 'اعتذر مقدم الخدمة (بحث عن بديل)',
            self::STATUS_PROVIDER_DELAYED => 'مقدم الخدمة متأخر',
            self::STATUS_CANCELLED => 'تم إلغاء الطلب',
            default => 'حالة غير محددة',
        };
    }

    /**
     * لون شارة الحالة (Tailwind).
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_ACCEPTANCE => 'bg-amber-100 text-amber-800 border-amber-200',
            self::STATUS_ACCEPTED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_ON_THE_WAY => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            self::STATUS_ARRIVED => 'bg-teal-100 text-teal-800 border-teal-200',
            self::STATUS_IN_PROGRESS => 'bg-purple-100 text-purple-800 border-purple-200',
            self::STATUS_PENDING_CONFIRMATION => 'bg-orange-100 text-orange-800 border-orange-200',
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::STATUS_UNDER_REVIEW => 'bg-rose-100 text-rose-800 border-rose-200',
            self::STATUS_NO_SHOW, self::STATUS_CANCELLED => 'bg-slate-100 text-slate-700 border-slate-200',
            self::STATUS_PROVIDER_DELAYED => 'bg-red-100 text-red-800 border-red-200',
            self::STATUS_PROVIDER_APOLOGIZED => 'bg-amber-100 text-amber-800 border-amber-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
