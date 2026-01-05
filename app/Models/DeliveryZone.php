<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'polygon_coordinates',
        'base_rate',
        'per_km_rate',
        'multiplier',
        'max_distance_km',
        'is_active',
        'color',
        'sort_order'
    ];

    protected $casts = [
        'polygon_coordinates' => 'array',
        'base_rate' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'multiplier' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_zone', 'slug');
    }

    /**
     * Check if a point is within this delivery zone
     */
    public function containsPoint(float $latitude, float $longitude): bool
    {
        if (!$this->polygon_coordinates || !$this->is_active) {
            return false;
        }

        // Simple polygon point-in-polygon test
        $polygon = $this->polygon_coordinates;
        $x = $longitude;
        $y = $latitude;
        
        $inside = false;
        $j = count($polygon) - 1;
        
        for ($i = 0; $i < count($polygon); $i++) {
            $xi = $polygon[$i][0];
            $yi = $polygon[$i][1];
            $xj = $polygon[$j][0];
            $yj = $polygon[$j][1];
            
            if ((($yi > $y) !== ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi)) {
                $inside = !$inside;
            }
            $j = $i;
        }
        
        return $inside;
    }

    /**
     * Calculate shipping cost for a given distance
     */
    public function calculateShippingCost(float $distanceKm): ?float
    {
        if (!$this->is_active) {
            return null;
        }

        // Check if distance exceeds maximum
        if ($this->max_distance_km && $distanceKm > $this->max_distance_km) {
            return null;
        }

        $cost = $this->base_rate + ($distanceKm * $this->per_km_rate);
        return $cost * $this->multiplier;
    }

    /**
     * Get active delivery zones
     */
    public static function getActiveZones()
    {
        return static::where('is_active', true)->get();
    }

    /**
     * Find zone containing a point
     */
    public static function findZoneContainingPoint(float $latitude, float $longitude): ?self
    {
        return static::where('is_active', true)->get()->first(function ($zone) use ($latitude, $longitude) {
            return $zone->containsPoint($latitude, $longitude);
        });
    }
}

