<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function getValue(
        string $key,
        mixed $default = null
    ): mixed {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var(
                $setting->value,
                FILTER_VALIDATE_BOOLEAN
            ),

            'integer' => (int) $setting->value,

            'json' => json_decode(
                $setting->value,
                true
            ),

            default => $setting->value,
        };
    }

    public static function setValue(
        string $key,
        mixed $value,
        string $type = 'string'
    ): static {
        if ($type === 'json') {
            $value = json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );
        } else {
            $value = (string) $value;
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
            ]
        );
    }
}
