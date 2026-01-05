<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShippingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description'
    ];

    /**
     * Get a setting value with fallback
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("shipping_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function setValue(string $key, $value, string $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description
            ]
        );

        Cache::forget("shipping_setting_{$key}");
        
        return $setting;
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return Cache::remember('all_shipping_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("shipping_setting_{$key}");
        }
        Cache::forget('all_shipping_settings');
    }
}

