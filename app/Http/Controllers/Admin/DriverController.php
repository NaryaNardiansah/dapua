<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /**
     * Display a listing of drivers
     */
    public function index()
    {
        $search = request('q');
        $status = request('status'); // all, active, blocked

        // Get drivers by role OR by is_driver flag (fallback)
        // This ensures we catch all drivers regardless of how they were created
        $driversQuery = User::where(function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('slug', 'driver');
            })->orWhere('is_driver', true);
        })->with('roles')->withCount('orders');

        if ($search) {
            $driversQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%")
                      ->orWhere('vehicle_number', 'like', "%$search%");
            });
        }

        if ($status === 'blocked') {
            $driversQuery->where('is_blocked', true);
        } elseif ($status === 'active') {
            $driversQuery->where('is_blocked', false);
        }

        $drivers = $driversQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Calculate statistics
        $totalDrivers = User::where(function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('slug', 'driver');
            })->orWhere('is_driver', true);
        })->count();
        
        $activeDrivers = User::where(function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('slug', 'driver');
            })->orWhere('is_driver', true);
        })->where('is_blocked', false)->count();
        
        $blockedDrivers = User::where(function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('slug', 'driver');
            })->orWhere('is_driver', true);
        })->where('is_blocked', true)->count();

        return view('admin.driver.index', compact('drivers', 'search', 'status', 'totalDrivers', 'activeDrivers', 'blockedDrivers'));
    }

    /**
     * Show the form for creating a new driver
     */
    public function create()
    {
        return view('admin.driver.create');
    }

    /**
     * Store a newly created driver
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['required', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'vehicle_number' => ['required', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'license_number' => $request->license_number,
            'email_verified_at' => now(), // Auto verify for admin-created drivers
            'is_driver' => true, // Set driver flag
            'is_available' => $request->boolean('is_available', true), // Default true
            'is_blocked' => $request->boolean('is_blocked', false), // Default false
        ]);

        // Automatically assign driver role
        $driverRole = Role::where('slug', 'driver')->first();
        if (!$driverRole) {
            // If role doesn't exist, create it
            $driverRole = Role::create([
                'name' => 'Driver',
                'slug' => 'driver',
                'description' => 'Driver role for delivery management',
                'is_active' => true,
            ]);
        }
        
        // Assign role if not already assigned
        if (!$user->hasRole('driver')) {
            $user->roles()->attach($driverRole->id);
        }
        
        // Refresh user to ensure roles are loaded
        $user->refresh();
        
        // Verify role assignment
        if (!$user->hasRole('driver')) {
            \Log::warning("Failed to assign driver role to user {$user->id}");
        }

        return redirect()->route('admin.driver.index')->with('status', "Driver {$user->name} berhasil didaftarkan");
    }

    /**
     * Display the specified driver
     */
    public function show(User $driver)
    {
        // Ensure this user is a driver
        if (!$driver->hasRole('driver') && !$driver->is_driver) {
            abort(404);
        }

        // Load relationships and counts
        $driver->loadCount([
            'driverOrders as total_orders_count',
            'driverOrders as completed_orders_count' => function($query) {
                $query->where('status', 'selesai');
            },
            'driverOrders as active_orders_count' => function($query) {
                $query->whereIn('status', ['diproses', 'dikirim']);
            }
        ]);
        
        // Get recent orders
        $recentOrders = $driver->driverOrders()
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_orders' => $driver->total_orders_count ?? 0,
            'completed_orders' => $driver->completed_orders_count ?? 0,
            'active_orders' => $driver->active_orders_count ?? 0,
            'total_revenue' => $driver->driverOrders()
                ->where('status', 'selesai')
                ->sum('grand_total'),
            'avg_rating' => $driver->driverOrders()
                ->whereNotNull('customer_rating')
                ->avg('customer_rating'),
        ];
        
        return view('admin.driver.show', compact('driver', 'stats', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified driver
     */
    public function edit(User $driver)
    {
        // Ensure this user is a driver
        if (!$driver->hasRole('driver') && !$driver->is_driver) {
            abort(404);
        }

        return view('admin.driver.edit', compact('driver'));
    }

    /**
     * Update the specified driver
     */
    public function update(Request $request, User $driver)
    {
        // Ensure this user is a driver
        if (!$driver->hasRole('driver') && !$driver->is_driver) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($driver->id)],
            'phone' => ['required', 'string', 'max:20'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'vehicle_number' => ['required', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:50'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'license_number' => $request->license_number,
            'is_available' => $request->has('is_available'),
            'is_blocked' => $request->has('is_blocked'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $driver->update($updateData);

        return redirect()->route('admin.driver.index')->with('status', "Data driver {$driver->name} berhasil diperbarui");
    }

    /**
     * Remove the specified driver
     */
    public function destroy(User $driver)
    {
        // Ensure this user is a driver
        if (!$driver->hasRole('driver') && !$driver->is_driver) {
            abort(404);
        }

        $driverName = $driver->name;
        
        // Remove driver role
        $driver->removeRole('driver');
        
        // If driver has no other roles, delete the user
        if ($driver->roles()->count() === 0) {
            $driver->delete();
            $message = "Driver {$driverName} berhasil dihapus";
        } else {
            $message = "Role driver berhasil dihapus dari {$driverName}";
        }

        return redirect()->route('admin.driver.index')->with('status', $message);
    }
}

