<?php

namespace App\Helpers;

/**
 * Setting Helper Class
 * 
 * Helper class for managing application settings
 */
class SettingHelper
{
    /**
     * Get a setting value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // Use ShippingSetting model if available
        if (class_exists(\App\Models\ShippingSetting::class)) {
            return \App\Models\ShippingSetting::getValue($key, $default);
        }
        
        // Fallback to config
        return config("app.{$key}", $default);
    }
    
    /**
     * Set a setting value
     * 
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return void
     */
    public static function set(string $key, $value, ?string $description = null): void
    {
        // Use ShippingSetting model if available
        if (class_exists(\App\Models\ShippingSetting::class)) {
            \App\Models\ShippingSetting::setValue($key, $value, $description);
        }
    }
}

