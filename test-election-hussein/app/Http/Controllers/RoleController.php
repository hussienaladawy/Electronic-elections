<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255'
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return back()->with('success', 'تمت إضافة الدور بنجاح');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);
        return back()->with('success', 'تم تحديث صلاحيات الدور');
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الدور');
    }
}
