<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ServiceRequest extends Model
{
    use HasFactory;

    /**
     * اسم الجدول في قاعدة البيانات.
     */
    protected $table = 'requests';

    // الحالات التشغيلية المعتمدة للطلب
    public const STATUS_PENDING_ACCEPTANCE = 'pending_acceptance'; // طلب متاح
    public const STATUS_ACCEPTED = 'accepted';                     // تم القبول
    public const STATUS_ASSIGNED = 'assigned';                     // تم التوكيل الرسمي
    public const STATUS_IN_PROGRESS = 'in_progress';               // قيد التنفيذ
    public const STATUS_PENDING_CONFIRMATION = 'pending_confirmation'; // بانتظار التأكيد
    public const STATUS_COMPLETED = 'completed';                   // مكتمل
    public const STATUS_UNDER_REVIEW = 'under_review';             // تحت المراجعة (اعتراض / مشكلة)
    public const STATUS_NO_PROVIDER_FOUND = 'no_provider_found';   // لم يتم العثور على مقدم خدمة
    public const STATUS_PROVIDER_APOLOGIZED = 'provider_apologized'; // اعتذر مقدم الخدمة
    public const STATUS_PROVIDER_DELAYED = 'provider_delayed';     // مقدم الخدمة متأخر
    public const STATUS_CANCELLED = 'cancelled';                   // تم إلغاء الطلب

    public const STATUSES = [
        self::STATUS_PENDING_ACCEPTANCE,
        self::STATUS_ACCEPTED,
        self::STATUS_ASSIGNED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_PENDING_CONFIRMATION,
        self::STATUS_COMPLETED,
        self::STATUS_PROVIDER_APOLOGIZED,
        self::STATUS_PROVIDER_DELAYED,
        self::STATUS_NO_PROVIDER_FOUND,
        self::STATUS_UNDER_REVIEW,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'public_id',
        'elder_id',
        'provider_id',
        'title',
        'service_type',
        'pricing_type',
        'proposed_price',
        'timing_type',
        'gender_preference',
        'incident_type',
        'description',
        'location',
        'scheduled_at',
        'status',
        'accepted_at',
        'assigned_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'proposed_price' => 'decimal:2',
    ];

    /**
     * ملف كبير السن صاحب الطلب (المستفيد).
     */
    public function elderProfile(): BelongsTo
    {
        return $this->belongsTo(ElderProfile::class, 'elder_id');
    }

    /**
     * اسم مستعار لعلاقة كبير السن.
     */
    public function elder(): BelongsTo
    {
        return $this->elderProfile();
    }

    /**
     * حساب المستخدم لكبير السن (عبر خاصية التوافق).
     */
    public function getUserAttribute(): ?User
    {
        return $this->elderProfile?->user;
    }

    /**
     * دالة علاقة توافقية للمستخدم صاحب الطلب.
     */
    public function user(): BelongsTo
    {
        // توفير علاقة BelongsTo افتراضية في حال تم الاستعلام عبر with('user')
        return $this->belongsTo(ElderProfile::class, 'elder_id');
    }

    /**
     * ملف مقدم الخدمة المسند إليه الطلب.
     */
    public function serviceProviderProfile(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }

    /**
     * اسم مستعار لعلاقة مقدم الخدمة.
     */
    public function provider(): BelongsTo
    {
        return $this->serviceProviderProfile();
    }

    /**
     * مستخدم مقدم الخدمة (عبر خاصية التوافق).
     */
    public function getAssignedProviderAttribute(): ?User
    {
        return $this->serviceProviderProfile?->user;
    }

    /**
     * دالة علاقة توافقية للمقدم المسند إليه الطلب.
     */
    public function assignedProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }

    /**
     * مرفقات الطلب.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class, 'request_id');
    }

    /**
     * التقييمات المرتبطة بالطلب.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'service_request_id');
    }

    /**
     * الشكاوى المرتبطة بالطلب.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'request_id');
    }

    /**
     * تقييم الخدمة للطلب المكتمل من كبير السن (توافقية مع الكود القديم).
     */
    public function review(): HasOne
    {
        return $this->hasOne(Rating::class, 'service_request_id')->where('rater_role', 'elder');
    }

    /**
     * تقييم كبير السن لمقدم الخدمة.
     */
    public function elderRating(): HasOne
    {
        return $this->hasOne(Rating::class, 'service_request_id')->where('rater_role', 'elder');
    }

    /**
     * تقييم مقدم الخدمة لكبير السن.
     */
    public function providerRating(): HasOne
    {
        return $this->hasOne(Rating::class, 'service_request_id')->where('rater_role', 'provider');
    }

    /**
     * نطاق الطلبات النشطة من منظور كبير السن.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING_ACCEPTANCE,
            self::STATUS_ACCEPTED,
            self::STATUS_ASSIGNED,
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
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * نطاق الطلبات المتاحة لمقدم خدمة معين.
     */
    public function scopeAvailableForProvider(Builder $query, User $provider): Builder
    {
        $providerProfileId = $provider->serviceProviderProfile?->id;

        return $query->where('status', self::STATUS_PENDING_ACCEPTANCE)
            ->whereNull('provider_id')
            ->when($providerProfileId, function ($q) use ($providerProfileId) {
                $q->where('elder_id', '!=', $providerProfileId);
            });
    }

    /**
     * توليد معرف تسلسلي فريد للطلب بصيغة REQ-1001.
     */
    public static function generatePublicId(): string
    {
        $driver = DB::connection()->getDriverName();
        $maxNum = null;

        try {
            if ($driver === 'mysql') {
                $maxNum = DB::table('requests')
                    ->where('public_id', 'like', '#REQ-%')
                    ->selectRaw('MAX(CAST(SUBSTRING(public_id, 6) AS UNSIGNED)) as max_num')
                    ->value('max_num');
            } elseif ($driver === 'sqlite') {
                $maxNum = DB::table('requests')
                    ->where('public_id', 'like', '#REQ-%')
                    ->selectRaw('MAX(CAST(SUBSTR(public_id, 6) AS INTEGER)) as max_num')
                    ->value('max_num');
            }
        } catch (\Throwable) {
            $maxNum = null;
        }

        if ($maxNum === null || (int) $maxNum < 1000) {
            $recentPublicIds = self::where('public_id', 'like', '#REQ-%')->pluck('public_id');
            $highest = 1000;
            foreach ($recentPublicIds as $pid) {
                if (preg_match('/^#REQ-(\d+)$/', $pid, $m)) {
                    $val = (int) $m[1];
                    if ($val > $highest) {
                        $highest = $val;
                    }
                }
            }
            $next = $highest + 1;
        } else {
            $next = (int) $maxNum + 1;
        }

        // ضمان عدم حدوث أي تكرار مع أي سجل حالي
        while (self::where('public_id', '#REQ-' . $next)->exists()) {
            $next++;
        }

        return '#REQ-' . $next;
    }

    /**
     * الترتيب الرقمي للخطوة الحالية في مسار الطلب.
     */
    public function getStepIndexAttribute(): int
    {
        return match ($this->status) {
            self::STATUS_PENDING_ACCEPTANCE => 1,
            self::STATUS_ACCEPTED, self::STATUS_ASSIGNED => 2,
            self::STATUS_IN_PROGRESS => 3,
            self::STATUS_PENDING_CONFIRMATION => 4,
            self::STATUS_COMPLETED => 5,
            default => 2,
        };
    }

    /**
     * هل مسموح إظهار رقم هاتف الطرف الآخر؟
     * مسموح فقط في: assigned, in_progress, pending_confirmation, completed
     * ولا يظهر أبدًا في: pending_acceptance, accepted, cancelled
     */
    public function canRevealContactPhone(): bool
    {
        return in_array($this->status, [
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PENDING_CONFIRMATION,
            self::STATUS_COMPLETED,
        ], true);
    }

    /**
     * هل يمكن لمقدم الخدمة الاعتذار عن الطلب الآن؟
     */
    public function canBeApologized(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_ASSIGNED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PROVIDER_DELAYED,
        ], true);
    }

    /**
     * هل يمكن للمستفيد إلغاء الطلب؟
     * مسموح في: pending_acceptance, accepted, no_provider_found, provider_apologized, provider_delayed
     * وممنوع تماماً في: assigned, in_progress, pending_confirmation
     */
    public function canBeCancelledByElderly(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_ACCEPTANCE,
            self::STATUS_ACCEPTED,
            self::STATUS_NO_PROVIDER_FOUND,
            self::STATUS_PROVIDER_APOLOGIZED,
            self::STATUS_PROVIDER_DELAYED,
        ], true);
    }

    /**
     * معالجة انتهاء المواعيد للطلبات غير المنفذة:
     * 1. الطلبات المتاحة التي حل موعدها دون قبول -> no_provider_found
     * 2. الطلبات المسندة التي حل موعدها دون بدء التنفيذ -> provider_delayed
     */
    public static function processScheduleExpirations(): void
    {
        $now = now();

        // 1. طلبات لم يقبلها أحد حتى حلول الموعد
        $unclaimed = self::where('status', self::STATUS_PENDING_ACCEPTANCE)
            ->where('scheduled_at', '<=', $now)
            ->get();

        foreach ($unclaimed as $req) {
            $req->update([
                'status' => self::STATUS_NO_PROVIDER_FOUND,
            ]);

            $elderUserId = $req->elderProfile?->user_id;
            if ($elderUserId) {
                Notification::create([
                    'user_id' => $elderUserId,
                    'type' => 'no_provider_found',
                    'message' => "لم يتوفر مقدم خدمة للطلب {$req->public_id} قبل موعده. يمكنك إعادة الجدولة أو إلغاء الطلب.",
                ]);
            }
        }

        // 2. طلبات مسندة حل موعدها دون بدء التنفيذ
        $delayed = self::whereIn('status', [self::STATUS_ASSIGNED, self::STATUS_ACCEPTED])
            ->where('scheduled_at', '<=', $now)
            ->whereNull('started_at')
            ->get();

        foreach ($delayed as $req) {
            $req->update([
                'status' => self::STATUS_PROVIDER_DELAYED,
                'incident_type' => 'delay',
            ]);

            $elderUserId = $req->elderProfile?->user_id;
            if ($elderUserId) {
                Notification::create([
                    'user_id' => $elderUserId,
                    'type' => 'provider_delayed',
                    'message' => "تأخر مقدم الخدمة عن موعد الطلب {$req->public_id}. يمكنك الانتظار أو طلب بديل أو إلغاء الطلب.",
                ]);
            }
        }
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
                self::STATUS_ASSIGNED,
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
            self::STATUS_ASSIGNED => 'تم التوكيل الرسمي',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_PENDING_CONFIRMATION => 'بانتظار تأكيد المستفيد',
            self::STATUS_COMPLETED => 'مكتمل وتم التقييم',
            self::STATUS_UNDER_REVIEW => 'تحت المراجعة والاعتراض',
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
            self::STATUS_ACCEPTED, self::STATUS_ASSIGNED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_IN_PROGRESS => 'bg-purple-100 text-purple-800 border-purple-200',
            self::STATUS_PENDING_CONFIRMATION => 'bg-orange-100 text-orange-800 border-orange-200',
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::STATUS_UNDER_REVIEW => 'bg-rose-100 text-rose-800 border-rose-200',
            self::STATUS_CANCELLED => 'bg-slate-100 text-slate-700 border-slate-200',
            self::STATUS_PROVIDER_DELAYED => 'bg-red-100 text-red-800 border-red-200',
            self::STATUS_PROVIDER_APOLOGIZED => 'bg-amber-100 text-amber-800 border-amber-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
