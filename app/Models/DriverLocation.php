<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'order_id',
        'latitude',
        'longitude',
        'accuracy',
        'speed',
        'heading',
        'status',
        'last_seen_at',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'accuracy' => 'decimal:2',
        'speed' => 'decimal:2',
        'heading' => 'decimal:2',
        'last_seen_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Update driver location
     */
    public static function updateLocation(User $driver, float $latitude, float $longitude, array $options = []): self
    {
        return self::create([
            'driver_id' => $driver->id,
            'order_id' => $options['order_id'] ?? null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracy' => $options['accuracy'] ?? null,
            'speed' => $options['speed'] ?? null,
            'heading' => $options['heading'] ?? null,
            'status' => $options['status'] ?? 'online',
            'last_seen_at' => now(),
            'metadata' => $options['metadata'] ?? [],
        ]);
    }

    /**
     * Get latest location for driver
     */
    public static function getLatestLocation(User $driver): ?self
    {
        return self::where('driver_id', $driver->id)
            ->orderBy('last_seen_at', 'desc')
            ->first();
    }

    /**
     * Get active drivers near location
     */
    public static function getNearbyDrivers(float $latitude, float $longitude, float $radiusKm = 10): \Illuminate\Database\Eloquent\Collection
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        return self::selectRaw("
                *,
                ({$earthRadius} * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance
            ", [$latitude, $longitude, $latitude])
            ->where('status', 'online')
            ->where('last_seen_at', '>=', now()->subMinutes(5)) // Active within last 5 minutes
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->with('driver')
            ->get();
    }

    /**
     * Calculate distance between two points
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    /**
     * Get driver's current status
     */
    public static function getDriverStatus(User $driver): string
    {
        $latest = self::getLatestLocation($driver);
        
        if (!$latest) {
            return 'offline';
        }
        
        if ($latest->last_seen_at->diffInMinutes(now()) > 5) {
            return 'offline';
        }
        
        return $latest->status;
    }
}

