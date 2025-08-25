<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Assistant;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Notification;

class IntegratedController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للنظام
     */
    public function welcome()
    {
        
        $activeElections = Election::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
            
        $totalVoters = Voter::where('status', true)->count();
        $totalVotes = Vote::where('is_verified', true)->count();
        
        return view('welcome', compact('activeElections', 'totalVoters', 'totalVotes'));
    }
    
    /**
     * عرض صفحة تسجيل الدخول الموحدة
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    /**
     * معالجة تسجيل الدخول الموحد
     */
  public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|in:super_admin,admin,assistant,voter'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $credentials = $request->only('email', 'password');
        $userType = $request->input('user_type');
        
        // تحديد الحارس المناسب
        $guard = $userType;
        
        
        // محاولة تسجيل الدخول
        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // توجيه المستخدم حسب نوعه
            $redirectRoute = match($userType) {
                'super_admin' => 'super_admin.dashboard',
                'admin' => 'admin.dashboard',
                'assistant' => 'assistant.dashboard',
                'voter' => 'voter.dashboard',
                default => 'home'
            };
            
            return redirect()->intended(route($redirectRoute));
        }
        
        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة'
        ])->withInput();
    }

    
    /**
     * تسجيل الخروج الموحد
     */
    public function logout(Request $request)
    {
        $guards = ['super_admin', 'admin', 'assistant', 'voter'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
                break;
            }
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح');
    }
    
    /**
     * عرض صفحة تسجيل ناخب جديد
     */
    public function showVoterRegistrationForm()
    {
        return view('voter.register');
    }
    
    /**
     * معالجة تسجيل ناخب جديد
     */
    public function registerVoter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:voters,email',
            'national_id' => 'required|string|unique:voters,national_id',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'province' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
            'city' => 'required|string|max:100'
            
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'province.required' => 'المحافظة مطلوبة',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // التحقق من العمر (18 سنة على الأقل)
        $age = now()->diffInYears($request->date_of_birth);
        if ($age < 18) {
            return back()->withErrors(['date_of_birth' => 'يجب أن يكون عمرك 18 سنة على الأقل للتسجيل'])->withInput();
        }
        
        try {
            $voter = Voter::create([
                'name' => $request->name,
                'email' => $request->email,
                'national_id' => $request->national_id,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'province' => $request->province,
                'password' => Hash::make($request->password),
                'status' => true,
                'notification_preferences' => [
                    'channels' => ['in_app', 'email'],
                    'send_time' => '09:00',
                    'language' => 'ar',
                    'types' => ['announcement', 'reminder', 'educational']
                ]
            ]);
            
            return redirect()->route('auth.login')
                ->with('success', 'تم تسجيلك بنجاح! يمكنك الآن تسجيل الدخول');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى'])
                ->withInput();
        }
    }
    
    /**
     * عرض الانتخابات المتاحة للتصويت
     */
    public function availableElections()
    {
        $elections = Election::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with(['candidates' => function($query) {
                $query->orderBy('display_order');
            }])
            ->orderBy('start_date')
            ->get();
            
        return view('voting.elections', compact('elections'));
    }
    
    /**
     * عرض النتائج العامة للانتخابات المكتملة
     */
    public function publicResults($electionId)
    {
        $election = Election::where('id', $electionId)
            ->where('status', 'completed')
            ->firstOrFail();
            
        $results = $this->calculateElectionResults($election);
        
        return view('public.results', compact('election', 'results'));
    }
    
    /**
     * صفحة التحقق من صحة الصوت
     */
    public function showVerifyVotePage()
    {
        return view('public.verify_vote');
    }
    
    /**
     * التحقق من صحة الصوت
     */
    public function verifyVote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string|size:20'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        
        $vote = Vote::where('verification_code', $request->verification_code)
            ->where('is_verified', true)
            ->with(['election', 'candidate', 'voter'])
            ->first();
            
        if (!$vote) {
            return back()->withErrors(['verification_code' => 'رمز التحقق غير صحيح أو الصوت غير موجود']);
        }
        
        $verificationData = [
            'vote_hash' => $vote->vote_hash,
            'election_name' => $vote->election->name,
            'candidate_name' => $vote->candidate->name,
            'voted_at' => $vote->voted_at,
            'verified_at' => $vote->verified_at,
            'is_valid' => true
        ];
        
        return view('public.vote_verification', compact('verificationData'));
    }
    
    /**
     * حساب نتائج الانتخابات
     */
    private function calculateElectionResults($election)
    {
        $totalVotes = Vote::where('election_id', $election->id)
            ->where('is_verified', true)
            ->count();
            
        $candidateResults = Candidate::where('election_id', $election->id)
            ->withCount(['votes' => function($query) {
                $query->where('is_verified', true);
            }])
            ->orderBy('votes_count', 'desc')
            ->get()
            ->map(function($candidate) use ($totalVotes) {
                $percentage = $totalVotes > 0 ? ($candidate->votes_count / $totalVotes) * 100 : 0;
                
                return [
                    'candidate' => $candidate,
                    'votes' => $candidate->votes_count,
                    'percentage' => round($percentage, 2)
                ];
            });
            
        return [
            'total_votes' => $totalVotes,
            'candidates' => $candidateResults,
            'winner' => $candidateResults->first()
        ];
    }
    
    /**
     * إحصائيات النظام العامة
     */
    public function systemStats()
    {
        $stats = [
            'elections' => [
                'total' => Election::count(),
                'active' => Election::where('status', 'active')->count(),
                'completed' => Election::where('status', 'completed')->count(),
                'upcoming' => Election::where('status', 'scheduled')->count(),
            ],
            'votes' => [
                'total' => Vote::count(),
                'verified' => Vote::where('is_verified', true)->count(),
                'today' => Vote::whereDate('voted_at', today())->count(),
                'this_week' => Vote::whereBetween('voted_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'users' => [
                'voters' => Voter::where('status', true)->count(),
                'super_admins' => SuperAdmin::count(),
                'admins' => Admin::where('status', true)->count(),
                'assistants' => Assistant::where('status', true)->count(),
            ],
            'notifications' => [
                'total' => Notification::count(),
                'sent' => Notification::where('status', 'sent')->count(),
                'scheduled' => Notification::where('status', 'scheduled')->count(),
                'pending' => Notification::where('status', 'pending')->count(),
            ]
        ];
        
        return response()->json($stats);
    }
    
    /**
     * فحص صحة النظام
     */
    public function healthCheck()
    {
        $checks = [
            'database' => true,
            'voting_system' => true,
            'notification_system' => true,
            'user_management' => true
        ];
        
        try {
            // فحص قاعدة البيانات
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            
            // فحص الجداول الأساسية
            $tableChecks = [
                'elections' => Election::count() >= 0,
                'votes' => Vote::count() >= 0,
                'notifications' => Notification::count() >= 0,
                'voters' => Voter::count() >= 0,
                'super_admins' => SuperAdmin::count() >= 0,
            ];
            
            $checks = array_merge($checks, $tableChecks);
            
        } catch (\Exception $e) {
            $checks['database'] = false;
            $checks['error'] = $e->getMessage();
        }
        
        $isHealthy = collect($checks)->every(function ($check) {
            return $check === true;
        });
        
        $status = $isHealthy ? 200 : 500;
        
        return response()->json([
            'status' => $isHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks
        ], $status);
    }
}

