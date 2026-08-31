<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'attempt_number',
        'provider_id',
        'scheduled_at',
        'location',
        'outcome',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'attempt_number' => 'integer',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}

