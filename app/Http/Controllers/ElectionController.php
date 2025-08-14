<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vote;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    /**
     * عرض قائمة الانتخابات
     */
    public function index()
    {
        $elections = Election::with(['createdBy', 'updatedBy'])
            ->withCount(['candidates', 'votes'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('super_admin.elections.index', compact('elections'));
    }

    /**
     * عرض نموذج إنشاء انتخابات جديدة
     */
    public function create()
    {
        return view('super_admin.elections.create');
    }

    /**
     * حفظ انتخابات جديدة
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['presidential', 'parliamentary', 'local', 'referendum'])],
            'settings' => 'nullable|array',
            'registration_start' => 'required|date|after_or_equal:now',
            'registration_end'   => 'required|date|after:registration_start',
            'start_date'         => 'required|date|after:registration_end',
            'end_date'           => 'required|date|after:start_date',
], [
    'registration_start.after_or_equal' => 'يجب أن يكون تاريخ بدء التسجيل في الوقت الحالي أو بعده.',
    'registration_end.after'           => 'يجب أن يكون تاريخ انتهاء التسجيل بعد تاريخ بدء التسجيل.',
    'start_date.after'                  => 'يجب أن يكون تاريخ بدء الانتخاب بعد انتهاء التسجيل.',
    'end_date.after'                    => 'يجب أن يكون تاريخ انتهاء الانتخاب بعد بدء الانتخاب.',
]);
        

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $election = Election::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'settings' => $request->settings,
            'status' => $request->status,
            'created_by' => auth('super_admin')->id()
        ]);

        return redirect()->route('super_admin.elections.show', $election)
            ->with('success', 'تم إنشاء الانتخابات بنجاح');
    }

    /**
     * عرض تفاصيل انتخابات محددة
     */
    public function show(Election $election)
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

        return view('super_admin.elections.show', compact('election', 'stats'));
    }

    /**
     * عرض نموذج تعديل الانتخابات
     */
    public function edit(Election $election)
    {
        return view('super_admin.elections.edit', compact('election'));
    }

    /**
     * تحديث بيانات الانتخابات
     */
    public function update(Request $request, Election $election)
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
            'updated_by' => auth('super_admin')->id()
        ]);

        return redirect()->route('super_admin.elections.show', $election)
            ->with('success', 'تم تحديث بيانات الانتخابات بنجاح');
    }

    /**
     * حذف الانتخابات
     */
    public function destroy(Election $election)
    {
        // منع حذف الانتخابات النشطة أو التي بها أصوات
        if ($election->status === 'active' || $election->votes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الانتخابات النشطة أو التي تحتوي على أصوات');
        }

        $election->delete();

        return redirect()->route('super_admin.elections.index')
            ->with('success', 'تم حذف الانتخابات بنجاح');
    }

    /**
     * تفعيل الانتخابات
     */
    public function activate(Election $election)
    {
        // التحقق من وجود مرشحين
        if ($election->candidates()->where('status', true)->count() < 2) {
            return redirect()->back()
                ->with('error', 'يجب وجود مرشحين اثنين على الأقل لتفعيل الانتخابات');
        }

        $election->update([
            'status' => 'active',
            'updated_by' => auth('super_admin')->id()
        ]);

        return redirect()->back()
            ->with('success', 'تم تفعيل الانتخابات بنجاح');
    }

    /**
     * إنهاء الانتخابات
     */
    public function complete(Election $election)
    {
        $election->update([
            'status' => 'completed',
            'updated_by' => auth('super_admin')->id()
        ]);

        return redirect()->back()
            ->with('success', 'تم إنهاء الانتخابات بنجاح');
    }

    /**
     * إلغاء الانتخابات
     */
    public function cancel(Election $election)
    {
        $election->update([
            'status' => 'cancelled',
            'updated_by' => auth('super_admin')->id()
        ]);

        return redirect()->back()
            ->with('success', 'تم إلغاء الانتخابات');
    }

    // ==================== إدارة المرشحين ====================

    /**
     * عرض قائمة المرشحين لانتخابات محددة
     */
    public function candidates(Election $election)
    {
        $candidates = $election->candidates()
            ->with(['createdBy', 'updatedBy'])
            ->withCount('votes')
            ->orderBy('order_number')
            ->paginate(4);

        return view('super_admin.elections.candidates', compact('election', 'candidates'));
    }
    //    عرض تفاصيل مرشح معين
      
     public function showCandidate(Election $election, Candidate $candidate)
     {
         return view("super_admin.elections.show_candidate", compact("election", "candidate"));
     }

    /**
     * عرض نموذج إضافة مرشح جديد
     */
    public function createCandidate(Election $election)
    {
        $nextOrderNumber = $election->candidates()->max('order_number') + 1;
        return view('super_admin.elections.create_candidate', compact('election', 'nextOrderNumber'));
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
            'created_by' => auth('super_admin')->id()
        ]);

        return redirect()->route('super_admin.elections.candidates', $election)
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

        return view('super_admin.elections.edit_candidate', compact('election', 'candidate'));
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
            'updated_by' => auth('super_admin')->id()
        ];

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }
            $updateData['image'] = $request->file('image')->store('candidates', 'public');
        }

        $candidate->update($updateData);

        return redirect()->route('super_admin.elections.candidates', $election)
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

        return redirect()->route('super_admin.elections.candidates', $election)
            ->with('success', 'تم حذف المرشح بنجاح');
    }
}

