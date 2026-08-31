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

    public const STATUS_PENDING_ACCEPTANCE = 'pending_acceptance';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PENDING_CONFIRMATION = 'pending_confirmation';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_NO_PROVIDER_FOUND = 'no_provider_found';
    public const STATUS_PROVIDER_APOLOGIZED = 'provider_apologized';
    public const STATUS_PROVIDER_DELAYED = 'provider_delayed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'public_id',
        'user_id',
        'assigned_provider_id',
        'title',
        'description',
        'location',
        'scheduled_at',
        'status',
        'attempts_count',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'attempts_count' => 'integer',
    ];

    /**
     * كبير السن صاحب الطلب.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * مقدم الخدمة المسند إليه الطلب (إن وجد).
     */
    public function assignedProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_provider_id');
    }

    /**
     * سجل محاولات الطلب.
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
     * نطاق الطلبات النشطة.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING_ACCEPTANCE,
            self::STATUS_ACCEPTED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PENDING_CONFIRMATION,
        ]);
    }

    /**
     * نطاق الطلبات التي بحاجة إلى إجراء.
     */
    public function scopeNeedsAction(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_NO_PROVIDER_FOUND,
            self::STATUS_PROVIDER_APOLOGIZED,
            self::STATUS_PROVIDER_DELAYED,
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
        return $query->where('status', self::STATUS_CANCELLED);
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
     * اسم التبويب التابع له الطلب.
     */
    public function getTabAttribute(): string
    {
        if (
            in_array($this->status, [
                self::STATUS_PENDING_ACCEPTANCE,
                self::STATUS_ACCEPTED,
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
            self::STATUS_PENDING_ACCEPTANCE => 'بانتظار قبول مقدم خدمة',
            self::STATUS_ACCEPTED => 'تم قبول الطلب',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_PENDING_CONFIRMATION => 'بانتظار تأكيدك',
            self::STATUS_COMPLETED => 'تم تنفيذ الطلب',
            self::STATUS_NO_PROVIDER_FOUND => 'لم يتم العثور على مقدم خدمة',
            self::STATUS_PROVIDER_APOLOGIZED => 'اعتذر مقدم الخدمة',
            self::STATUS_PROVIDER_DELAYED => 'مقدم الخدمة متأخر',
            self::STATUS_CANCELLED => 'تم إلغاء الطلب',
            default => 'حالة غير محددة',
        };
    }
}

