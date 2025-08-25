<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Admin;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Assistant;
use App\Models\Candidate;
use App\Models\SuperAdmin;
use App\Notifications\NewVoterRegisteredNotification;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Exports\VoterImportTemplateExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = auth('admin')->user();
        $notifications = $admin->unreadNotifications; // Or ->notifications for all

        $totalElections = Election::count();
        $activeElections = Election::where("status", "active")->count();
        $completedElections = Election::where("status", "completed")->count();
        $totalVotesCast = Vote::count();
        $totalAdmins = Admin::count();
        $totalAssistants = Assistant::count();
        $totalCandidates = Candidate::count();
        $totalVoters = Voter::count(); // Overall voter count

        $latestElections = Election::orderBy("created_at", "desc")->take(5)->get();
        $latestVoters = Voter::orderBy("created_at", "desc")->take(5)->get();

        return view("admin.dashboard", compact(
            "totalElections", "activeElections", "completedElections", "totalVotesCast",
            "totalAdmins", "totalAssistants", "totalCandidates", "totalVoters",
            "latestElections", "latestVoters",
            "notifications" // Pass notifications to the view
        ));
        

    }

    public function indexElections()
    {
        $elections = Election::all();
        return view('admin.elections.index', compact('elections'));
    }

    public function createElection()
    {
        return view('admin.elections.create');
    }

    public function storeElection(Request $request)
    {
        $request->validate([
            
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Election::create($request->all());

        return redirect()->route('admin.elections.index')->with('success', 'تم إنشاء الانتخابات بنجاح.');
    }

/**
     * عرض تفاصيل انتخابات محددة
     */
    public function showElection(Election $election)
    {
        $election->load(['candidates' => function($query) {
            $query->orderBy('order_number');
        }, 'createdBy', 'updatedBy']);

        $stats = [
            'total_candidates' => $election->candidates()->count(),
            'active_candidates' => $election->candidates()->where('status', true)->count(),
            'total_votes' => $election->votes()->count(),
            'verified_votes' => $election->votes()->where('is_verified', true)->count(),
        ];

        return view('admin.elections.show', compact('election', 'stats'));
    }

    /**
     * عرض نموذج تعديل الانتخابات
     */
    public function editElection(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    /**
     * تحديث بيانات الانتخابات
     */
    public function updateElection(Request $request, Election $election)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:presidential,parliamentary,local,referendum',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'status' => 'required|in:draft,active,completed,cancelled',
            'settings' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $election->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'status' => $request->status,
            'settings' => $request->settings,
            'updated_by' => auth('admin')->id()
        ]);

        return redirect()->route('admin.elections.show', $election)
            ->with('success', 'تم تحديث بيانات الانتخابات بنجاح');
    }

    /**
     * حذف الانتخابات
     */
    public function destroyElection(Election $election)
    {
        // منع حذف الانتخابات النشطة أو التي بها أصوات
        if ($election->status === 'active' || $election->votes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الانتخابات النشطة أو التي تحتوي على أصوات');
        }

        $election->delete();

        return redirect()->route('admin.elections.index')
            ->with('success', 'تم حذف الانتخابات بنجاح');
    }


    public function indexVoters()
    {
        $voters = Voter::all();
        return view("admin.voters.index", compact("voters"));
    }

    public function createVoter()
    {
        return view('admin.voters.create');
    }

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
            'created_by' => auth('admin')->id(),
        ]);

        // Notify SuperAdmins and Admins
        $superAdmins = SuperAdmin::all();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new NewVoterRegisteredNotification($voter));
        }

        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->notify(new NewVoterRegisteredNotification($voter));
        }

        return redirect()->route('admin.voters.index')
            ->with('success', 'تم إضافة الناخب بنجاح');
    }
/**
     * عرض تفاصيل ناخب
     */
    public function showVoter(Voter $voter)
    {
        $voter->load(['createdBy', 'updatedBy']);
        return view('admin.voters.show', compact('voter'));
    }

    /**
     * عرض نموذج تعديل ناخب
     */
    public function editVoter(Voter $voter)
    {
        return view('admin.voters.edit', compact('voter'));
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
            'updated_by' => auth('admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $voter->update($updateData);

        return redirect()->route('admin.voters.index')
            ->with('success', 'تم تحديث بيانات الناخب بنجاح');
    }

    /**
     * حذف ناخب
     */
    public function destroyVoter(Voter $voter)
    {
        $voter->delete();

        return redirect()->route('admin.voters.index')
            ->with('success', 'تم حذف الناخب بنجاح');
    }





    public function indexAdmins()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('admin.admins.create');
    }

   
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
            'created_by' => auth('admin')->id(),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إضافة الادمن بنجاح');
    }
        /**
     * عرض تفاصيل ادمن
     */
    public function showAdmin(Admin $admin)
    {
        $admin->load(['createdBy', 'updatedBy']);
        return view('admin.admins.show', compact('admin'));
    }
    public function adminReports(Admin $admin){
        $admins = Admin::with(['createdBy', 'updatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.admins.reports', compact('admins'));
    }


    /**
     * عرض نموذج تعديل ادمن
     */
    public function editAdmin(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
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
            'updated_by' => auth('admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث بيانات الادمن بنجاح');
    }

    /**
     * حذف ادمن
     */
    public function destroyAdmin(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')
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
        
        return view('admin.assistants.index', compact('assistants'));
    }

    /**
     * عرض نموذج إضافة مساعد جديد
     */
    public function createAssistant()
    {
        $admins = Admin::where('status', true)->get();
        return view('admin.assistants.create', compact('admins'));
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
            'created_by' => auth('admin')->id(),
        ]);

        return redirect()->route('admin.assistants.index')
            ->with('success', 'تم إضافة المساعد بنجاح');
    }

    /**
     * عرض تفاصيل مساعد
     */
    public function showAssistant(Assistant $assistant)
    {
        $assistant->load(['assignedAdmin', 'createdBy', 'updatedBy']);
        return view('admin.assistants.show', compact('assistant'));
    }

    /**
     * عرض نموذج تعديل مساعد
     */
    public function editAssistant(Assistant $assistant)
    {
        $admins = Admin::where('status', true)->get();
        return view('admin.assistants.edit', compact('assistant', 'admins'));
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
            'updated_by' => auth('admin')->id(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $assistant->update($updateData);

        return redirect()->route('admin.assistants.index')
            ->with('success', 'تم تحديث بيانات المساعد بنجاح');
    }

    /**
     * حذف مساعد
     */
    public function destroyAssistant(Assistant $assistant)
    {
        $assistant->delete();

        return redirect()->route('admin.assistants.index')
            ->with('success', 'تم حذف المساعد بنجاح');
    }

 
      public function exportAssistants(Request $request)
    {
        return Excel::download(new AssistantsExport($request->all()), "assistants.xlsx");
    }
        /**
     * عرض قائمة المرشحين لانتخابات محددة
     */
    public function indexCandidates(Election $election)
    {
        $candidates = $election->candidates()
            ->with(['createdBy', 'updatedBy'])
            ->withCount('votes')
            ->orderBy('order_number')
            ->paginate(4);

        return view('admin.elections.candidates', compact('election', 'candidates'));
    }
    //    عرض تفاصيل مرشح معين
      
     public function showCandidate(Election $election, Candidate $candidate)
     {
         return view("admin.elections.show_candidate", compact("election", "candidate"));
     }

    /**
     * عرض نموذج إضافة مرشح جديد
     */
    public function createCandidate(Election $election)
    {
        $nextOrderNumber = $election->candidates()->max('order_number') + 1;
        return view('admin.elections.create_candidate', compact('election', 'nextOrderNumber'));
    }

    /**
     * حفظ مرشح جديد
     */
    public function storeCandidate(Request $request, Election $election)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'party_affiliation' => 'nullable|string|max:255',
            'program' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order_number' => 'required|integer|min:1|unique:candidates,order_number,NULL,id,election_id,' . $election->id,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('candidates', 'public');
        }

        $candidate = Candidate::create([
            'election_id' => $election->id,
            'name' => $request->name,
            'biography' => $request->biography,
            'party_affiliation' => $request->party_affiliation,
            'program' => $request->program,
            'image' => $imagePath,
            'order_number' => $request->order_number,
            'status' => $request->status ?? true,
            'created_by' => auth('admin')->id()
        ]);

        return redirect()->route('admin.elections.candidates', $election)
            ->with('success', 'تم إضافة المرشح بنجاح');
    }

    /**
     * عرض نموذج تعديل المرشح
     */
    public function editCandidate(Election $election, Candidate $candidate)
    {
        // التحقق من أن المرشح ينتمي للانتخابات
        if ($candidate->election_id !== $election->id) {
            abort(404);
        }

        return view('admin.elections.edit_candidate', compact('election', 'candidate'));
    }

    /**
     * تحديث بيانات المرشح
     */
    public function updateCandidate(Request $request, Election $election, Candidate $candidate)
    {
        // التحقق من أن المرشح ينتمي للانتخابات
        if ($candidate->election_id !== $election->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'party_affiliation' => 'nullable|string|max:255',
            'program' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order_number' => 'required|integer|min:1|unique:candidates,order_number,' . $candidate->id . ',id,election_id,' . $election->id,
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'biography' => $request->biography,
            'party_affiliation' => $request->party_affiliation,
            'program' => $request->program,
            'order_number' => $request->order_number,
            'status' => $request->status ?? true,
            'updated_by' => auth('admin')->id()
        ];

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }
            $updateData['image'] = $request->file('image')->store('candidates', 'public');
        }

        $candidate->update($updateData);

        return redirect()->route('admin.elections.candidates', $election)
            ->with('success', 'تم تحديث بيانات المرشح بنجاح');
    }

    /**
     * حذف المرشح
     */
    public function destroyCandidate(Election $election, Candidate $candidate)
    {
        // التحقق من أن المرشح ينتمي للانتخابات
        if ($candidate->election_id !== $election->id) {
            abort(404);
        }

        // منع حذف المرشح إذا كان له أصوات
        if ($candidate->votes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف المرشح الذي حصل على أصوات');
        }

        // حذف صورة المرشح
        if ($candidate->image) {
            Storage::disk('public')->delete($candidate->image);
        }

        $candidate->delete();

        return redirect()->route('admin.elections.candidates', $election)
            ->with('success', 'تم حذف المرشح بنجاح');
    }

}

