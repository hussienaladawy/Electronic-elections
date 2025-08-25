<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - Integrated System
|--------------------------------------------------------------------------
|
| الروتات المتكاملة لنظام الانتخابات الشامل
| يجمع بين الإصدار الأول والثاني في نظام موحد
|
*/

// ==================== الصفحة الرئيسية ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==================== روتات المصادقة العامة ====================
Route::prefix('auth')->name('auth.')->group(function () {
    // تسجيل الدخول الموحد
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        // منطق تسجيل الدخول الموحد
        $credentials = $request->only('email', 'password');
        $userType = $request->input('user_type', 'voter');
        
        $guard = match($userType) {
            'super_admin' => 'super_admin',
            'admin' => 'admin', 
            'assistant' => 'assistant',
            'voter' => 'voter',
            default => 'voter'
        };
        
        if (auth($guard)->attempt($credentials)) {
            return redirect()->intended(route($userType . '.dashboard'));
        }
        
        return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
    })->name('login.submit');
    
    // تسجيل الخروج الموحد
    Route::post('/logout', function () {
        $guards = ['super_admin', 'admin', 'assistant', 'voter'];
        foreach ($guards as $guard) {
            if (auth($guard)->check()) {
                auth($guard)->logout();
                break;
            }
        }
        return redirect()->route('home');
    })->name('logout');
});








// روتات التصويت
Route::prefix("voting")->name("voting.")->middleware('auth:voter')->group(function () {
    Route::get("/", [VotingController::class, "availableElections"])->name("voting.index"); // Added voting.index route
    Route::get("/elections", [VotingController::class, "availableElections"])->name("elections");
     Route::get("/election/{election}", [VotingController::class, "showVotingPage"])->name("show");
    Route::post("/election/{election}/vote", [VotingController::class, "submitVote"])->name("submit");
    Route::get("/confirmation/{voteHash}", [VotingController::class, "showConfirmation"])->name("confirmation");
   
});
  Route::middleware(['auth:voter'])->group(function () {
        Route::get('/election/{election}', [VotingController::class, 'showVotingPage'])->name('vote');
        Route::post('/election/{election}/vote', [VotingController::class, 'submitVote'])->name('submit');
        Route::get('/confirmation/{voteHash}', [VotingController::class, 'showConfirmation'])->name('confirmation');
    });
// ==================== روتات التصويت الإلكتروني ====================
// Route::prefix('voting')->name('voting.')->group(function () {
//     // عرض الانتخابات المتاحة (عام)
//     Route::get('/elections', [VotingController::class, 'availableElections'])->name('elections');
    
//     // عرض صفحة التصويت (يتطلب مصادقة)
   
    
//     // التحقق من صحة الصوت (عام)
//     Route::post('/verify', [VotingController::class, 'verifyVote'])->name('verify');
    
//     // عرض النتائج (عام للانتخابات المكتملة)
//     Route::get('/results/{election}', [VotingController::class, 'showResults'])->name('results');
// });

// ==================== روتات عامة ====================

// صفحة النتائج العامة
// Route::get('/public/results/{election}', [VotingController::class, 'showResults'])->name('public.results');

// صفحة التحقق من الصوت العامة
// Route::get('/public/verify-vote', function () {
//     return view('public.verify_vote');
// })->name('public.verify_vote');

// Route::post('/public/verify-vote', [VotingController::class, 'verifyVote'])->name('public.verify_vote.submit');

// ==================== روتات API ====================
Route::prefix('api/v1')->name('api.v1.')->group(function () {
    
    // إحصائيات التصويت المباشرة
    Route::get('/elections/{election}/live-stats', [VotingController::class, 'liveStats'])
        ->middleware(['auth:super_admin,admin'])
        ->name('election.live_stats');
    
    // تصدير النتائج
    Route::get('/elections/{election}/export/{format?}', [VotingController::class, 'exportResults'])
        ->middleware(['auth:super_admin,admin'])
        ->name('election.export');
    
    // معالجة الإشعارات المجدولة
    Route::post('/notifications/process-scheduled', [NotificationController::class, 'processScheduledNotifications'])
        ->name('notifications.process_scheduled');
    
    // بيانات المخططات البيانية
    Route::get('/reports/chart-data', [ReportsController::class, 'chartData'])
        ->middleware(['auth:super_admin,admin'])
        ->name('reports.chart_data');
});

// ==================== روتات الصحة والمراقبة ====================
Route::prefix('health')->name('health.')->group(function () {
    
    // فحص صحة النظام
    Route::get('/check', function () {
        $checks = [
            'database' => true,
            'voting_system' => true,
            'notification_system' => true,
            'reporting_system' => true
        ];
        
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            
            $tableChecks = [
                'elections' => \App\Models\Election::count() >= 0,
                'votes' => \App\Models\Vote::count() >= 0,
                'notifications' => \App\Models\Notification::count() >= 0,
                'super_admins' => \App\Models\SuperAdmin::count() >= 0,
                'voters' => \App\Models\Voter::count() >= 0,
            ];
            
            $checks = array_merge($checks, $tableChecks);
            
        } catch (\Exception $e) {
            $checks['database'] = false;
            $checks['error'] = $e->getMessage();
        }
        
        $status = collect($checks)->every(function ($check) {
            return $check === true;
        }) ? 200 : 500;
        
        return response()->json([
            'status' => $status === 200 ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks
        ], $status);
    })->name('check');
    
    // إحصائيات الأداء
    Route::get('/stats', function () {
        return response()->json([
            'elections' => [
                'total' => \App\Models\Election::count(),
                'active' => \App\Models\Election::where('status', 'active')->count(),
                'completed' => \App\Models\Election::where('status', 'completed')->count(),
            ],
            'votes' => [
                'total' => \App\Models\Vote::count(),
                'verified' => \App\Models\Vote::where('is_verified', true)->count(),
                'today' => \App\Models\Vote::whereDate('voted_at', today())->count(),
            ],
            'notifications' => [
                'total' => \App\Models\Notification::count(),
                'sent' => \App\Models\Notification::where('status', 'sent')->count(),
                'scheduled' => \App\Models\Notification::where('status', 'scheduled')->count(),
            ],
            'users' => [
                'voters' => \App\Models\Voter::where('status', true)->count(),
                'super_admins' => \App\Models\SuperAdmin::count(),
                'admins' => \App\Models\Admin::where('status', true)->count(),
                'assistants' => \App\Models\Assistant::where('status', true)->count(),
            ]
        ]);
    })->name('stats');
});

