<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'available_days',
        'available_from',
        'available_to',
        'is_available',
        'offered_services',
        'service_city',
        'coverage_radius_km',
        'commitment_score',
        'punctuality_rate',
        'completion_rate',
        'response_rate',
    ];

    protected $casts = [
        'available_days' => 'array',
        'offered_services' => 'array',
        'is_available' => 'boolean',
        'coverage_radius_km' => 'integer',
        'commitment_score' => 'integer',
        'punctuality_rate' => 'integer',
        'completion_rate' => 'integer',
        'response_rate' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

