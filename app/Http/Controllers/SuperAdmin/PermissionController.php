<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a dashboard to manage permissions for all roles.
     */
    public function index()
    {
        // Define the roles/guards we want to manage
        $roles = Role::whereIn('name', ['super_admin', 'admin', 'assistant', 'voter'])->get();

        // Get all unique permission names across all guards
        $permissions = Permission::select('name')->distinct()->get()->pluck('name');

        // Prepare a matrix of role permissions
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->name] = $role->permissions->pluck('name')->toArray();
        }

        return view('super_admin.permissions.index', compact('roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the permissions for all roles from the dashboard.
     */
    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'sometimes|array',
        ]);

        $submittedPermissions = $request->input('permissions', []);

        $roles = Role::whereIn('name', ['super_admin', 'admin', 'assistant', 'voter'])->get();

        foreach ($roles as $role) {
            // Get the permissions submitted for this role
            $roleSubmittedPermissions = $submittedPermissions[$role->name] ?? [];
            
            // Get the full permission objects to sync
            $permissionsToSync = Permission::whereIn('name', $roleSubmittedPermissions)->get();

            $role->syncPermissions($permissionsToSync);
        }

        return redirect()->route('super_admin.permissions.index')
            ->with('success', 'Permissions updated successfully.');
    }
}