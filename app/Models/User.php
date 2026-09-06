<?php

namespace App\Models;

use App\Notifications\EhsanResetPasswordNotification;
use App\Notifications\EhsanVerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'rejection_reason',
        'profile_picture_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ملف كبير السن.
     */
    public function elderProfile(): HasOne
    {
        return $this->hasOne(ElderProfile::class);
    }

    /**
     * ملف مقدم الخدمة.
     */
    public function serviceProviderProfile(): HasOne
    {
        return $this->hasOne(ServiceProviderProfile::class);
    }

    /**
     * حساب الإدارة.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * التحقق مما إذا كان المستخدم مديراً للنظام.
     */
    public function isAdmin(): bool
    {
        return $this->relationLoaded('admin')
            ? $this->admin !== null
            : $this->admin()->exists();
    }

    /**
     * هل المستخدم مقدم خدمة (متطوع)؟
     */
    public function isProvider(): bool
    {
        return $this->relationLoaded('serviceProviderProfile')
            ? $this->serviceProviderProfile !== null
            : $this->serviceProviderProfile()->exists();
    }

    /**
     * هل المستخدم كبير سن (مستفيد)؟
     */
    public function isElder(): bool
    {
        return $this->getRoleAttribute() === 'elder';
    }

    /**
     * تحديد الدور الفعلي للمستخدم: elder, provider, admin, super_admin.
     */
    public function getRoleAttribute(): string
    {
        if ($this->isAdmin()) {
            return $this->admin?->admin_level ?? 'admin';
        }

        if ($this->isProvider()) {
            return 'provider';
        }

        return 'elder';
    }

    /**
     * الإشعارات الخاصة بالمستخدم.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * الشكاوى المقدمة من قبل المستخدم.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'reporter_id');
    }

    /**
     * طلبات الخدمة المنشأة من قبل هذا المستخدم (كبير السن عبر ملفه).
     */
    public function serviceRequests(): HasManyThrough
    {
        return $this->hasManyThrough(ServiceRequest::class, ElderProfile::class, 'user_id', 'elder_id');
    }

    /**
     * طلبات الخدمة المسندة إلى هذا المستخدم (مقدم الخدمة عبر ملفه).
     */
    public function assignedServiceRequests(): HasManyThrough
    {
        return $this->hasManyThrough(ServiceRequest::class, ServiceProviderProfile::class, 'user_id', 'provider_id');
    }

    /**
     * التقييمات المعطاة.
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(Rating::class, 'elderly_id');
    }

    /**
     * التقييمات المستلمة.
     */
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(Rating::class, 'provider_id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new EhsanResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EhsanVerifyEmailNotification());
    }
}
