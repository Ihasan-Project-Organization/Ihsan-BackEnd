<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class ServiceProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'full_name', 'birth_date', 'id_document_path',
        'good_conduct_cert_path', 'tier', 'completed_tasks_count',
        'average_rating', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'average_rating' => 'decimal:1',
            'is_available' => 'boolean',
        ];
    }

    public function setTierAttribute(int $value): void
    {
        if (! in_array($value, [1, 2, 3], true)) {
            throw new InvalidArgumentException('The tier must be 1, 2, or 3.');
        }

        $this->attributes['tier'] = $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'provider_id');
    }

    public function volunteerCertificates(): HasMany
    {
        return $this->hasMany(VolunteerCertificate::class, 'provider_id');
    }
}
