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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new EhsanResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EhsanVerifyEmailNotification());
    }
}
