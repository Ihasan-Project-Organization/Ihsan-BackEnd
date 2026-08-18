<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'elder_id', 'provider_id', 'service_type', 'classification',
        'proposed_price', 'timing_type', 'scheduled_at', 'gender_preference',
        'description', 'location_text', 'status', 'cancelled_reason',
    ];

    protected function casts(): array
    {
        return ['proposed_price' => 'decimal:2', 'scheduled_at' => 'datetime'];
    }

    public function elder(): BelongsTo
    {
        return $this->belongsTo(ElderProfile::class, 'elder_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class, 'request_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'request_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'request_id');
    }
}
