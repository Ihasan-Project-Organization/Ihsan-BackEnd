<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_birth',
        'phone',
        'identity_number',
        'city',
        'address',
        'housing_type',
        'extra_info',
        'identity_document_path',
        'conduct_document_path',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
