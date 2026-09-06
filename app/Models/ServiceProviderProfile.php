<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'phone_number',
        'id_document_path',
        'good_conduct_cert_path',
        'tier',
        'completed_tasks_count',
        'average_rating',
        'is_available',
        'reliability_incidents_count',
    ];

    protected $attributes = [
        'phone_number' => '0590000000',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'tier' => 'integer',
        'completed_tasks_count' => 'integer',
        'average_rating' => 'decimal:1',
        'is_available' => 'boolean',
        'reliability_incidents_count' => 'integer',
    ];

    /**
     * حساب المستخدم المرتبط.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * طلبات الخدمة المسندة لمقدم الخدمة.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'provider_id');
    }

    /**
     * الاسم المستعار لطلبات الخدمة.
     */
    public function requests(): HasMany
    {
        return $this->serviceRequests();
    }

    /**
     * شهادات التطوع الممنوحة لمقدم الخدمة.
     */
    public function volunteerCertificates(): HasMany
    {
        return $this->hasMany(VolunteerCertificate::class, 'provider_id');
    }

    /**
     * سجل حوادث عدم الموثوقية (اعتذارات / تأخير).
     */
    public function reliabilityIncidents(): HasMany
    {
        return $this->hasMany(ProviderReliabilityIncident::class, 'provider_id');
    }

    /**
     * تحديث مستوى مقدم الخدمة (Tier) بناءً على عدد المهام ومتوسط التقييم.
     *
     * عتبات الترقية والتخفيض المعتمدة:
     * - Tier 3: 30 مهمة مكتملة فأكثر بمتوسط 4.3+
     * - Tier 2: 10 مهام مكتملة فأكثر بمتوسط 4.0+
     * - Tier 1: الافتراضي أو عند تراجع المعايير عن حدود Tier 2
     */
    public function updateTier(): int
    {
        $tasks = (int) $this->completed_tasks_count;
        $rating = (float) ($this->average_rating ?? 0.0);
        $oldTier = (int) $this->tier;
        $newTier = 1;

        if ($tasks >= 30 && $rating >= 4.3) {
            $newTier = 3;
        } elseif ($tasks >= 10 && $rating >= 4.0) {
            $newTier = 2;
        } else {
            $newTier = 1;
        }

        if ($newTier !== $oldTier) {
            $this->update(['tier' => $newTier]);

            if ($this->user_id) {
                if ($newTier > $oldTier) {
                    Notification::create([
                        'user_id' => $this->user_id,
                        'type' => 'tier_upgraded',
                        'message' => "تهانينا! تمت ترقية مستواك إلى المستوى {$newTier} (Tier {$newTier}) بفضل إتمامك {$tasks} مهمة بمتوسط تقييم {$rating}.",
                    ]);
                } else {
                    Notification::create([
                        'user_id' => $this->user_id,
                        'type' => 'tier_downgraded',
                        'message' => "تنبيه: تم تعديل مستواك إلى المستوى {$newTier} (Tier {$newTier}) نظراً لتغير متوسط تقييمك ({$rating}).",
                    ]);
                }
            }
        }

        return $newTier;
    }

    /**
     * تسجيل حادثة عدم موثوقية وفحص عتبة الـ 30 يوماً للتنبيه الإداري.
     */
    public function recordReliabilityIncident(string $incidentType = 'apology', ?int $requestId = null): void
    {
        $this->increment('reliability_incidents_count');

        $this->reliabilityIncidents()->create([
            'request_id' => $requestId,
            'incident_type' => $incidentType,
        ]);

        $this->checkReliabilityAlert();
    }

    /**
     * التحقق من عتبة حوادث آخر 30 يوماً وإرسال إشعار للإدارة عند بلوغها 3 فأكثر.
     */
    public function checkReliabilityAlert(): void
    {
        $recentCount = $this->reliabilityIncidents()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        if ($recentCount >= 3) {
            $admins = User::whereHas('admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'provider_reliability_alert',
                    'message' => "تنبيه إداري: سجّل مقدم الخدمة ({$this->full_name}) عدد {$recentCount} حوادث عدم موثوقية (اعتذار/تأخير) خلال آخر 30 يوماً.",
                ]);
            }
        }
    }
}
