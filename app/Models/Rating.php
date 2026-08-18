<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['request_id', 'rater_role', 'stars', 'comment', 'visible_to_provider'];

    protected function casts(): array
    {
        return ['visible_to_provider' => 'boolean'];
    }

    public function setStarsAttribute(int $value): void
    {
        if ($value < 1 || $value > 5) {
            throw new InvalidArgumentException('The stars value must be between 1 and 5.');
        }

        $this->attributes['stars'] = $value;
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
