<?php

namespace App\Models;

use App\Notifications\EhsanResetPasswordNotification;
use App\Notifications\EhsanVerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'account_type',
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

    public function registrationProfile(): HasOne
    {
        return $this->hasOne(RegistrationProfile::class);
    }

    /**
     * طلبات الخدمة المنشأة من قبل هذا المستخدم (كبير السن).
     */
    public function serviceRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    /**
     * طلبات الخدمة المسندة إلى هذا المستخدم (المتطوع).
     */
    public function assignedServiceRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'assigned_provider_id');
    }

    /**
     * التقييمات المعطاة.
     */
    public function givenReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceReview::class, 'elderly_id');
    }

    /**
     * التقييمات المستلمة.
     */
    public function receivedReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceReview::class, 'provider_id');
    }

    /**
     * إعدادات ومؤشرات مقدم الخدمة (المتطوع).
     */
    public function providerSetting(): HasOne
    {
        return $this->hasOne(ProviderSetting::class);
    }

    /**
     * الطلبات المتجاوزة من قبل هذا المتطوع.
     */
    public function dismissedRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProviderDismissedRequest::class);
    }

    /**
     * جلب أو إنشاء إعدادات التوفر والالتزام لمقدم الخدمة.
     */
    public function getOrCreateProviderSetting(): ProviderSetting
    {
        return $this->providerSetting()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'available_days' => ['sat', 'sun', 'mon', 'tue', 'wed', 'thu'],
                'available_from' => '08:00:00',
                'available_to' => '18:00:00',
                'is_available' => true,
                'offered_services' => ['grocery', 'medical_escort', 'medicine', 'home_help'],
                'service_city' => 'مدينة غزة',
                'coverage_radius_km' => 5,
                'commitment_score' => 92,
                'punctuality_rate' => 94,
                'completion_rate' => 97,
                'response_rate' => 88,
            ]
        );
    }

    public function isVolunteer(): bool
    {
        return $this->account_type === 'volunteer';
    }

    public function isElderly(): bool
    {
        return $this->account_type === 'elderly';
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
