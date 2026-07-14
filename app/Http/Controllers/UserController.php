<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Get all users with their associated roles
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Get all available roles to display in the edit form
        $roles = Role::all();
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // Sync roles (removes old ones, adds new ones)
        $user->syncRoles($request->roles ?? []);
        
        // Sync direct permissions
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users.index')->with('success', 'User rights updated successfully.');
    }
}
