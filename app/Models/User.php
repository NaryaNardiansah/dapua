<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_blocked',
        'is_driver',
        'driver_license',
        'vehicle_type',
        'vehicle_number',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'is_available',
        'photo',
        'phone',
        'provider',
        'provider_id',
        'avatar',
        'license_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_blocked' => 'boolean',
        'is_driver' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function driverOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    /**
     * Get user roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()->where('is_active', true)
            ->get()
            ->some(fn($role) => $role->hasPermission($permission));
    }

    /**
     * Assign role to user
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            // Force only one role by using sync instead of attach
            $this->roles()->sync([$role->id]);

            // Sync legacy columns
            $this->update([
                'is_admin' => ($roleSlug === 'admin'),
                'is_driver' => ($roleSlug === 'driver')
            ]);

            // Refresh to ensure roles are loaded
            $this->load('roles');
        }
    }
    /**
     * Remove role from user
     */
    public function removeRole(string $roleSlug): void
    {
        $role = Role::findBySlug($roleSlug);
        if ($role) {
            $this->roles()->detach($role);

            // Sync legacy columns
            if ($roleSlug === 'admin')
                $this->update(['is_admin' => false]);
            if ($roleSlug === 'driver')
                $this->update(['is_driver' => false]);
        }
    }

    /**
     * Sync user roles
     */
    public function syncRoles(array $roleSlugs): void
    {
        // Enforce only one role by taking the first one if multiple are provided
        $singleRoleSlug = !empty($roleSlugs) ? $roleSlugs[0] : null;

        if ($singleRoleSlug) {
            $this->assignRole($singleRoleSlug);
        } else {
            $this->roles()->detach();
            $this->update(['is_admin' => false, 'is_driver' => false]);
            $this->load('roles');
        }
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is driver
     */
    public function isDriver(): bool
    {
        return $this->hasRole('driver');
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    /**
     * Update driver location
     */
    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'last_location_update' => now()
        ]);

        return $this;
    }

    /**
     * Get available drivers near location
     */
    public static function getAvailableDriversNear($latitude, $longitude, $radiusKm = 10)
    {
        return static::where('is_driver', true)
            ->where('is_available', true)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->get()
            ->filter(function ($driver) use ($latitude, $longitude, $radiusKm) {
                $distance = static::calculateDistance(
                    $latitude,
                    $longitude,
                    $driver->current_latitude,
                    $driver->current_longitude
                );
                return $distance <= $radiusKm;
            })
            ->sortBy(function ($driver) use ($latitude, $longitude) {
                return static::calculateDistance(
                    $latitude,
                    $longitude,
                    $driver->current_latitude,
                    $driver->current_longitude
                );
            });
    }

    /**
     * Calculate distance between two points in kilometers
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}


