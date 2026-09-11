<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * جلب قيمة إعداد معين من جدول system_settings مع إمكانية تمرير قيمة افتراضية.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('system_settings')) {
                return $default;
            }

            $setting = static::where('key', $key)->first();

            return ($setting !== null && $setting->value !== null) ? $setting->value : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    /**
     * حفظ أو تحديث قيمة إعداد في النظام.
     */
    public static function set(string $key, mixed $value, ?string $description = null): static
    {
        $data = ['value' => (string) $value];

        if ($description !== null) {
            $data['description'] = $description;
        }

        return static::updateOrCreate(['key' => $key], $data);
    }
}
