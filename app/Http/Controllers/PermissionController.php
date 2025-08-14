<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
{
    $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
    $permissions = \Spatie\Permission\Models\Permission::all();

    return view('permissions.index', compact('roles', 'permissions'));
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255'
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return back()->with('success', 'تمت إضافة الصلاحية بنجاح');
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الصلاحية');
    }
}
