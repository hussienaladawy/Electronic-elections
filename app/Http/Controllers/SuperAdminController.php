<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Assistant;
use App\Models\Voter;
use App\Models\Election;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VoterImportTemplateExport;

class SuperAdminController extends Controller
{
    // ==================== إدارة السوبرادمن ====================
    
    /**
     * عرض قائمة السوبرادمن
     */
    public function indexSuperAdmins()
    {
        $superAdmins = SuperAdmin::with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('super_admin.super_admins.index', compact('superAdmins'));
    }

    /**
     * عرض نموذج إضافة سوبرادمن جديد
     */
    public function createSuperAdmin()
    {
        return view('super_admin.super_admins.create');
    }

    /**
     * حفظ سوبرادمن جديد
     */
    public function storeSuperAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:super_admins',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:super_admins',
            'national_id' => 'required|string|max:20|unique:super_admins',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $superAdmin = SuperAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'status' => $request->status ?? true,
            'created_by' => auth('super_admin')->id(),
        ]);

        return redirect()->route('super_admin.super_admins.index')
            ->with('success', 'تم إضافة السوبرادمن بنجاح');
    }

    /**
     * عرض تفاصيل سوبرادمن
     */
    public function showSuperAdmin(SuperAdmin $superAdmin)
    {
        $superAdmin->load(['createdBy', 'updatedBy']);
        return view('super_admin.super_admins.show', compact('superAdmin'));
    }

    /**
     * عرض نموذج تعديل سوبرادمن
     */
    public function editSuperAdmin(SuperAdmin $superAdmin)
    {
        return view('super_admin.super_admins.edit', compact('superAdmin'));
    }

    /**
     * تحديث بيانات سوبرادمن
     */
    public function updateSuperAdmin(Request $request, SuperAdmin $superAdmin)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:super_admins,email,' . $superAdmin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:super_admins,phone,' . $superAdmin->id,
            'national_id' => 'required|string|max:20|unique:super_admins,national_id,' . $superAdmin->id,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'status' => $request->status ?? true,
            'updated_by' => auth('super_admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $superAdmin->update($updateData);

        return redirect()->route('super_admin.super_admins.index')
            ->with('success', 'تم تحديث بيانات السوبرادمن بنجاح');
    }

    /**
     * حذف سوبرادمن
     */
    public function destroySuperAdmin(SuperAdmin $superAdmin)
    {
        // منع حذف السوبرادمن الحالي
        if ($superAdmin->id == auth('super_admin')->id()) {
            return redirect()->back()
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $superAdmin->delete();

        return redirect()->route('super_admin.super_admins.index')
            ->with('success', 'تم حذف السوبرادمن بنجاح');
    }

    // ==================== إدارة الادمن ====================
    
    /**
     * عرض قائمة الادمن
     */
    public function indexAdmins()
    {
        $admins = Admin::with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('super_admin.admins.index', compact('admins'));
    }

    /**
     * عرض نموذج إضافة ادمن جديد
     */
    public function createAdmin()
    {
        return view('super_admin.admins.create');
    }

    /**
     * حفظ ادمن جديد
     */
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:admins',
            'national_id' => 'required|string|max:20|unique:admins',
            'department' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'department' => $request->department,
            'permissions' => $request->permissions,
            'status' => $request->status ?? true,
            'created_by' => auth('super_admin')->id(),
        ]);

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'تم إضافة الادمن بنجاح');
    }

    /**
     * عرض تفاصيل ادمن
     */
    public function showAdmin(Admin $admin)
    {
        $admin->load(['createdBy', 'updatedBy']);
        return view('super_admin.admins.show', compact('admin'));
    }
    public function adminReports(Admin $admin){
        $admins = Admin::with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('super_admin.admins.reports', compact('admins'));
    }


    /**
     * عرض نموذج تعديل ادمن
     */
    public function editAdmin(Admin $admin)
    {
        return view('super_admin.admins.edit', compact('admin'));
    }

    /**
     * تحديث بيانات ادمن
     */
    public function updateAdmin(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:admins,phone,' . $admin->id,
            'national_id' => 'required|string|max:20|unique:admins,national_id,' . $admin->id,
            'department' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'department' => $request->department,
            'permissions' => $request->permissions,
            'status' => $request->status ?? true,
            'updated_by' => auth('super_admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'تم تحديث بيانات الادمن بنجاح');
    }

    /**
     * حذف ادمن
     */
    public function destroyAdmin(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('super_admin.admins.index')
            ->with('success', 'تم حذف الادمن بنجاح');
    }

    // ==================== إدارة المساعد ====================
    
    /**
     * عرض قائمة المساعدين
     */
    public function indexAssistants()
    {
        $assistants = Assistant::with(['assignedAdmin', 'createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('super_admin.assistants.index', compact('assistants'));
    }

    /**
     * عرض نموذج إضافة مساعد جديد
     */
    public function createAssistant()
    {
        $admins = Admin::where('status', true)->get();
        return view('super_admin.assistants.create', compact('admins'));
    }

    /**
     * حفظ مساعد جديد
     */
    public function storeAssistant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:assistants',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:assistants',
            'national_id' => 'required|string|max:20|unique:assistants',
            'assigned_admin_id' => 'nullable|exists:admins,id',
            'work_area' => 'nullable|string|max:255',
            'shift_time' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $assistant = Assistant::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'assigned_admin_id' => $request->assigned_admin_id,
            'work_area' => $request->work_area,
            'shift_time' => $request->shift_time,
            'status' => $request->status ?? true,
            'created_by' => auth('super_admin')->id(),
        ]);

        return redirect()->route('super_admin.assistants.index')
            ->with('success', 'تم إضافة المساعد بنجاح');
    }

    /**
     * عرض تفاصيل مساعد
     */
    public function showAssistant(Assistant $assistant)
    {
        $assistant->load(['assignedAdmin', 'createdBy', 'updatedBy']);
        return view('super_admin.assistants.show', compact('assistant'));
    }

    /**
     * عرض نموذج تعديل مساعد
     */
    public function editAssistant(Assistant $assistant)
    {
        $admins = Admin::where('status', true)->get();
        return view('super_admin.assistants.edit', compact('assistant', 'admins'));
    }

    /**
     * تحديث بيانات مساعد
     */
    public function updateAssistant(Request $request, Assistant $assistant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:assistants,email,' . $assistant->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:assistants,phone,' . $assistant->id,
            'national_id' => 'required|string|max:20|unique:assistants,national_id,' . $assistant->id,
            'assigned_admin_id' => 'nullable|exists:admins,id',
            'work_area' => 'nullable|string|max:255',
            'shift_time' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'assigned_admin_id' => $request->assigned_admin_id,
            'work_area' => $request->work_area,
            'shift_time' => $request->shift_time,
            'status' => $request->status ?? true,
            'updated_by' => auth('super_admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $assistant->update($updateData);

        return redirect()->route('super_admin.assistants.index')
            ->with('success', 'تم تحديث بيانات المساعد بنجاح');
    }

    /**
     * حذف مساعد
     */
    public function destroyAssistant(Assistant $assistant)
    {
        $assistant->delete();

        return redirect()->route('super_admin.assistants.index')
            ->with('success', 'تم حذف المساعد بنجاح');
    }

    // ==================== إدارة الناخبين ====================
    
    /**
     * عرض قائمة الناخبين
     */
    public function indexVoters()
    {
        $voters = Voter::with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('super_admin.voters.index', compact('voters'));
    }

    /**
     * عرض قائمة الانتخابات
     */
    public function indexElections()
    {
        $elections = Election::withCount(['candidates', 'votes'])
            ->with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('super_admin.elections.index', compact('elections'));
    }

    /**
     * عرض صفحة إدارة الصلاحيات
     */
    public function indexPermissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('super_admin.permissions.index', compact('roles', 'permissions'));
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        app()['cache']->forget('spatie.permission.cache');

        return redirect()->route('super_admin.permissions.index')->with('success', 'Role permissions updated successfully.');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create(['name' => $request->name]);

        app()['cache']->forget('spatie.permission.cache');

        return redirect()->route('super_admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();

        app()['cache']->forget('spatie.permission.cache');
        
        return redirect()->route('super_admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }

    /**
     * عرض نموذج إضافة ناخب جديد
     */
    public function createVoter()
    {
        return view('super_admin.voters.create');
    }

    /**
     * حفظ ناخب جديد
     */
    public function storeVoter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:voters',
            'national_id' => 'required|string|max:20|unique:voters',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'voting_center_id' => 'nullable|integer',
            'is_eligible' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $voter = Voter::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            // 'district' => $request->district,
            'voting_center_id' => $request->voting_center_id,
            'is_eligible' => $request->is_eligible ?? true,
            'status' => $request->status ?? true,
            'created_by' => auth('super_admin')->id(),
        ]);

        return redirect()->route('super_admin.voters.index')
            ->with('success', 'تم إضافة الناخب بنجاح');
    }

    /**
     * عرض تفاصيل ناخب
     */
    public function showVoter(Voter $voter)
    {
        $voter->load(['createdBy', 'updatedBy']);
        return view('super_admin.voters.show', compact('voter'));
    }

    /**
     * عرض نموذج تعديل ناخب
     */
    public function editVoter(Voter $voter)
    {
        return view('super_admin.voters.edit', compact('voter'));
    }

    /**
     * تحديث بيانات ناخب
     */
    public function updateVoter(Request $request, Voter $voter)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters,email,' . $voter->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:voters,phone,' . $voter->id,
            'national_id' => 'required|string|max:20|unique:voters,national_id,' . $voter->id,
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'voting_center_id' => 'nullable|integer',
            'is_eligible' => 'boolean',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'voting_center_id' => $request->voting_center_id,
            'is_eligible' => $request->is_eligible ?? true,
            'status' => $request->status ?? true,
            'updated_by' => auth('super_admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $voter->update($updateData);

        return redirect()->route('super_admin.voters.index')
            ->with('success', 'تم تحديث بيانات الناخب بنجاح');
    }

    /**
     * حذف ناخب
     */
    public function destroyVoter(Voter $voter)
    {
        $voter->delete();

        return redirect()->route('super_admin.voters.index')
            ->with('success', 'تم حذف الناخب بنجاح');
    }

    // ==================== دوال مساعدة ====================
    
    /**
     * لوحة التحكم الرئيسية للسوبرادمن
     */
    public function dashboard()
    {
        $stats = [
            'super_admins_count' => SuperAdmin::count(),
            'admins_count' => Admin::count(),
            'assistants_count' => Assistant::count(),
            'voters_count' => Voter::count(),
            'active_voters_count' => Voter::where('status', true)->count(),
            'eligible_voters_count' => Voter::where('is_eligible', true)->count(),
            'voted_count' => Voter::where('has_voted', 1)->count(),

        ];

        return view('super_admin.dashboard', compact('stats'));
    }

    /**
     * البحث في المستخدمين
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');

        $results = [];

        if ($type == 'all' || $type == 'super_admins') {
            $results['super_admins'] = SuperAdmin::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('national_id', 'like', "%{$query}%")
                ->limit(10)->get();
        }

        if ($type == 'all' || $type == 'admins') {
            $results['admins'] = Admin::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('national_id', 'like', "%{$query}%")
                ->limit(10)->get();
        }

        if ($type == 'all' || $type == 'assistants') {
            $results['assistants'] = Assistant::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('national_id', 'like', "%{$query}%")
                ->limit(10)->get();
        }

        if ($type == 'all' || $type == 'voters') {
            $results['voters'] = Voter::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('national_id', 'like', "%{$query}%")
                ->limit(10)->get();
        }

        return view('super_admin.search_results', compact('results', 'query', 'type'));
    }
    //    * تصدير بيانات المساعدين
     
    public function exportAssistants(Request $request)
    {
        return Excel::download(new AssistantsExport($request->all()), "assistants.xlsx");
    }

    /**
     * إجراءات مجمعة على المساعدين
     */
    public function bulkActionAssistants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "ids" => "required|array",
            "ids.*" => "exists:assistants,id",
            "action" => "required|in:activate,deactivate,delete"
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => $validator->errors()->first()], 400);
        }

        $ids = $request->input("ids");
        $action = $request->input("action");

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $assistant = Assistant::findOrFail($id);
                switch ($action) {
                    case 'activate':
                        $assistant->status = true;
                        break;
                    case 'deactivate':
                        $assistant->status = false;
                        break;
                    case 'delete':
                        $assistant->delete();
                        continue 2; // Skip to next iteration for delete
                }
                $assistant->save();
            }
            DB::commit();
            return response()->json(["success" => true, "message" => "تم تنفيذ الإجراء بنجاح"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => "حدث خطأ أثناء تنفيذ الإجراء: " . $e->getMessage()], 500);
        }
    }

    public function downloadVoterImportTemplate()
    {
        return Excel::download(new VoterImportTemplateExport, 'voters_template.xlsx');
    }
}
