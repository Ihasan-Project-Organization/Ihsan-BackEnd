<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AdminAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * المدير الذي نفّذ الإجراء.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * تسجيل عملية إدارية في السجل.
     */
    public static function log(
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $reason = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'admin_id'    => Auth::id(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'reason'      => $reason,
            'metadata'    => $metadata,
        ]);
    }

    /**
     * تسمية الإجراء باللغة العربية.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'approved_user'          => 'اعتماد حساب',
            'rejected_user'          => 'رفض طلب الحساب',
            'resubmission_requested' => 'طلب استكمال مستندات',
            'suspended_user'         => 'إيقاف حساب مستخدم',
            'reactivated_user'       => 'إعادة تفعيل حساب',
            'force_status_changed'   => 'تغيير حالة طلب إدارياً',
            'resolved_complaint'     => 'معالجة / إغلاق شكوى',
            'created_admin'          => 'إضافة مدير نظام جديد',
            'deleted_admin'          => 'حذف مدير نظام',
            'updated_settings'       => 'تحديث إعدادات وعتبات النظام',
            default                  => $this->action,
        };
    }

    /**
     * تنسيق شارة الإجراء ولونها وأيقونتها.
     */
    public function getBadgeDetailsAttribute(): array
    {
        return match ($this->action) {
            'approved_user', 'reactivated_user' => [
                'bg'    => '#ecfdf5',
                'color' => '#065f46',
                'border'=> '#a7f3d0',
                'icon'  => 'fa-circle-check',
            ],
            'rejected_user', 'suspended_user', 'deleted_admin' => [
                'bg'    => '#fef2f2',
                'color' => '#991b1b',
                'border'=> '#fecaca',
                'icon'  => 'fa-circle-xmark',
            ],
            'resubmission_requested' => [
                'bg'    => '#fffbeb',
                'color' => '#92400e',
                'border'=> '#fde68a',
                'icon'  => 'fa-file-signature',
            ],
            'force_status_changed' => [
                'bg'    => '#eff6ff',
                'color' => '#1e40af',
                'border'=> '#bfdbfe',
                'icon'  => 'fa-arrow-right-arrow-left',
            ],
            'resolved_complaint' => [
                'bg'    => '#faf5ff',
                'color' => '#6b21a8',
                'border'=> '#e9d5ff',
                'icon'  => 'fa-shield-halved',
            ],
            'created_admin' => [
                'bg'    => '#eef2ff',
                'color' => '#3730a3',
                'border'=> '#c7d2fe',
                'icon'  => 'fa-user-shield',
            ],
            'updated_settings' => [
                'bg'    => '#f0fdfa',
                'color' => '#115e59',
                'border'=> '#99f6e4',
                'icon'  => 'fa-sliders',
            ],
            default => [
                'bg'    => '#f3f4f6',
                'color' => '#374151',
                'border'=> '#e5e7eb',
                'icon'  => 'fa-circle-dot',
            ],
        };
    }

    /**
     * وصف الكيان المتأثر بالعملية.
     */
    public function getTargetDescriptionAttribute(): string
    {
        if (! $this->target_type) {
            return 'إعدادات النظام العامة';
        }

        $meta = $this->metadata ?? [];

        return match ($this->target_type) {
            'User'           => isset($meta['user_name']) ? "مستخدم: {$meta['user_name']}" : "مستخدم #{$this->target_id}",
            'ServiceRequest' => isset($meta['public_id']) ? "طلب خدمة #{$meta['public_id']}" : "طلب #{$this->target_id}",
            'Complaint'      => "شكوى إدارية #{$this->target_id}",
            'Admin'          => isset($meta['name']) ? "مدير: {$meta['name']}" : (isset($meta['email']) ? "مدير ({$meta['email']})" : "مدير #{$this->target_id}"),
            'SystemSetting'  => 'إعدادات المنصة وعتبات الترقية',
            default          => "{$this->target_type} #{$this->target_id}",
        };
    }
}
