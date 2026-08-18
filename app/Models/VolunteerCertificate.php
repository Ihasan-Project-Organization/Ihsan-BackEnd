<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerCertificate extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id', 'certificate_number', 'issued_at'];

    protected function casts(): array
    {
        return ['issued_at' => 'date'];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'provider_id');
    }
}
