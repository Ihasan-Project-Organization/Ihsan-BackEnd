<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'city',
        'phone_number',
        'id_document_path',
    ];

    protected $attributes = [
        'phone_number' => '0590000000',
    ];

    /**
     * حساب المستخدم المرتبط.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * طلبات الخدمة الخاصة بكبير السن.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'elder_id');
    }

    /**
     * الاسم المستعار لطلبات الخدمة.
     */
    public function requests(): HasMany
    {
        return $this->serviceRequests();
    }
}
