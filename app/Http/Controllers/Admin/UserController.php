<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $search = request('q');
        $status = request('status'); // all, active, blocked
        $role = request('role'); // all, admin, driver, customer
        
        $usersQuery = User::withCount('orders')->with('roles');
        
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            });
        }
        
        if ($status === 'blocked') {
            $usersQuery->where('is_blocked', true);
        } elseif ($status === 'active') {
            $usersQuery->where('is_blocked', false);
        }
        
        if ($role && $role !== 'all') {
            $usersQuery->whereHas('roles', function($query) use ($role) {
                $query->where('slug', $role);
            });
        }
        
        $users = $usersQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Get all roles for filter dropdown
        $roles = Role::active()->get();
        
        // Calculate statistics for quick stats
        $totalUsers = User::count();
        $activeUsers = User::where('is_blocked', false)->count();
        $blockedUsers = User::where('is_blocked', true)->count();
        $adminUsers = User::whereHas('roles', function($query) {
            $query->where('slug', 'admin');
        })->count();
        $newUsersToday = User::where('created_at', '>=', now()->startOfDay())->count();
        
        return view('admin.users.index', compact(
            'users', 'search', 'status', 'role', 'roles',
            'totalUsers', 'activeUsers', 'blockedUsers', 'adminUsers', 'newUsersToday'
        ));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->with('orderItems.product')->latest()->limit(10);
        }]);
        
        return view('admin.users.show', compact('user'));
    }

    public function block(User $user)
    {
        $user->update(['is_blocked' => true]);
        return redirect()->route('admin.users.index')->with('status', "User {$user->name} telah diblokir");
    }

    public function unblock(User $user)
    {
        $user->update(['is_blocked' => false]);
        return redirect()->route('admin.users.index')->with('status', "User {$user->name} telah diaktifkan kembali");
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('status', 'Tidak boleh menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('status', "User {$user->name} telah dihapus permanen");
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,slug'
        ]);

        $user->assignRole($request->role);
        
        return back()->with('status', "Role {$request->role} berhasil diberikan kepada {$user->name}");
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,slug'
        ]);

        $user->removeRole($request->role);
        
        return back()->with('status', "Role {$request->role} berhasil dihapus dari {$user->name}");
    }

    /**
     * Sync user roles
     */
    public function syncRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,slug'
        ]);

        $user->syncRoles($request->roles ?? []);
        
        return back()->with('status', "Roles berhasil disinkronkan untuk {$user->name}");
    }
}
