<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderReliabilityIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'request_id',
        'incident_type',
    ];

    /**
     * ملف مقدم الخدمة المعني.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }

    /**
     * طلب الخدمة المرتبط بالحادثة.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
