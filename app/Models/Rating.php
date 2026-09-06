<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'service_request_id',
        'elderly_id',
        'provider_id',
        'stars',
        'comment',
        'rater_role',
        'visible_to_provider',
    ];

    protected $casts = [
        'stars' => 'integer',
        'visible_to_provider' => 'boolean',
    ];

    /**
     * طلب الخدمة المقيم.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    /**
     * حساب كبير السن.
     */
    public function elderly(): BelongsTo
    {
        return $this->belongsTo(User::class, 'elderly_id');
    }

    /**
     * حساب مقدم الخدمة.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * خاصية التوافق مع الكود السابق (rating -> stars).
     */
    public function getRatingAttribute(): ?int
    {
        return $this->stars;
    }
}
