<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SuperAdmin\PermissionController;

// ==================== روتات السوبرادمن ====================
Route::prefix('super_admin')->name('super_admin.')->middleware(['auth:super_admin'])->group(function () {
   
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // إدارة السوبرادمن
    Route::prefix('super_admins')->name('super_admins.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'indexSuperAdmins'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'createSuperAdmin'])->name('create');
        Route::post('/', [SuperAdminController::class, 'storeSuperAdmin'])->name('store');
        Route::get('/{superAdmin}', [SuperAdminController::class, 'showSuperAdmin'])->name('show');
        Route::get('/{superAdmin}/edit', [SuperAdminController::class, 'editSuperAdmin'])->name('edit');
        Route::put('/{superAdmin}', [SuperAdminController::class, 'updateSuperAdmin'])->name('update');
        Route::delete('/{superAdmin}', [SuperAdminController::class, 'destroySuperAdmin'])->name('destroy');
    });
    
    // إدارة الأدمن
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'indexAdmins'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'createAdmin'])->name('create');
        Route::post('/export', [SuperAdminController::class, 'exportAdmins'])->name('export');
        Route::post('/', [SuperAdminController::class, 'storeAdmin'])->name('store');
        Route::get('/{admin}', [SuperAdminController::class, 'showAdmin'])->name('show');
        Route::get('/{admin}/edit', [SuperAdminController::class, 'editAdmin'])->name('edit');
        Route::put('/{admin}', [SuperAdminController::class, 'updateAdmin'])->name('update');
        Route::delete('/{admin}', [SuperAdminController::class, 'destroyAdmin'])->name('destroy');
    });
    
    // إدارة المساعدين
    Route::prefix('assistants')->name('assistants.')->group(function () {

        Route::get('/', [SuperAdminController::class, 'indexAssistants'])->name('index');
        Route::get('export', [SuperAdminController::class,'exportAssistants'])->name('export');
        Route::get('toggle-status/{assistant}', [SuperAdminController::class,'toggleStatus'])->name('toggle-status');
        Route::post("/bulk-action", [SuperAdminController::class, "bulkActionAssistants"])->name("bulk-action");
        Route::get('/create', [SuperAdminController::class, 'createAssistant'])->name('create');
        Route::post('/', [SuperAdminController::class, 'storeAssistant'])->name('store');
        Route::get('/{assistant}', [SuperAdminController::class, 'showAssistant'])->name('show');
        Route::get('/{assistant}/edit', [SuperAdminController::class, 'editAssistant'])->name('edit');
        Route::put('/{assistant}', [SuperAdminController::class, 'updateAssistant'])->name('update');
        Route::delete('/{assistant}', [SuperAdminController::class, 'destroyAssistant'])->name('destroy');
    });
    
    // إدارة الناخبين
    Route::prefix('voters')->name('voters.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'indexVoters'])->name('index');
        Route::get('/toggle-status/{voter}', [SuperAdminController::class,'toggleStatus'])->name('toggle-status');
        Route::get('/send-password-reset', [SuperAdminController::class,'sendPasswordResetMail'])->name('send-password-reset');
        Route::get('/send-message', [SuperAdminController::class,'sendMessageMail'])->name('send-message');
        Route::get('/send-welcome-email', [SuperAdminController::class,'sendWelcomeMail'])->name('send-welcome-email');
        Route::get('/export', [SuperAdminController::class, 'exportVoters'])->name('export');
        Route::get('/create', [SuperAdminController::class, 'createVoter'])->name('create');
        Route::post('/', [SuperAdminController::class, 'storeVoter'])->name('store');
        Route::get('/{voter}', [SuperAdminController::class, 'showVoter'])->name('show');
        Route::get('/{voter}/edit', [SuperAdminController::class, 'editVoter'])->name('edit');
        Route::put('/{voter}', [SuperAdminController::class, 'updateVoter'])->name('update');
        Route::delete('/{voter}', [SuperAdminController::class, 'destroyVoter'])->name('destroy');
        Route::get('/import/template', [SuperAdminController::class, 'downloadVoterImportTemplate'])->name('import.template');
    });
    
    // إدارة الانتخابات
    Route::prefix('elections')->name('elections.')->group(function () {
        Route::get('/', [ElectionController::class, 'index'])->name('index');
        Route::get('/create', [ElectionController::class, 'create'])->name('create');
        Route::post('/', [ElectionController::class, 'store'])->name('store');
        Route::get('/{election}', [ElectionController::class, 'show'])->name('show');
        Route::get('/{election}/edit', [ElectionController::class, 'edit'])->name('edit');
        Route::put('/{election}', [ElectionController::class, 'update'])->name('update');
        Route::delete('/{election}', [ElectionController::class, 'destroy'])->name('destroy');
        Route::get("/results/{election}", [VotingController::class, "showResults"])->name("results");
        Route::get("export", [VotingController::class,'exportResults'])->name('export');
        Route::get('change-status', [VotingController::class,'changeStatus'])->name('change-status');
        
        

        
        // إجراءات الانتخابات
        Route::post('/{election}/activate', [ElectionController::class, 'activate'])->name('activate');
        Route::post('/{election}/complete', [ElectionController::class, 'complete'])->name('complete');
        Route::post('/{election}/cancel', [ElectionController::class, 'cancel'])->name('cancel');
        
        // إدارة المرشحين
        Route::get('/{election}/candidates', [ElectionController::class, 'candidates'])->name('candidates');
        Route::get('/{election}/candidates/create', [ElectionController::class, 'createCandidate'])->name('candidates.create');
        Route::get("/{election}/candidates/{candidate}", [ElectionController::class, "showCandidate"])->name("candidates.show");

        Route::post('/{election}/candidates', [ElectionController::class, 'storeCandidate'])->name('candidates.store');
        Route::get('/{election}/candidates/{candidate}/edit', [ElectionController::class, 'editCandidate'])->name('candidates.edit');
        Route::put('/{election}/candidates/{candidate}', [ElectionController::class, 'updateCandidate'])->name('candidates.update');
        Route::delete('/{election}/candidates/{candidate}', [ElectionController::class, 'destroyCandidate'])->name('candidates.destroy');
    });
    
    // نظام التقارير والمخططات
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [ReportsController::class, 'dashboard'])->name('dashboard');
        Route::get('/election/{election}/results', [ReportsController::class, 'electionResults'])->name('election.results');
        Route::get('/reports', [ReportsController::class,'index'])->name('index');
        Route::get('/compare', [ReportsController::class, 'compareElections'])->name('compare');
        Route::post('/compare', [ReportsController::class, 'compareElections'])->name('compare.post');
        Route::get('/voters', [ReportsController::class, 'voterStatistics'])->name('voters');
        Route::get('/trends', [ReportsController::class, 'trends'])->name('trends');
        Route::get('/chart-data', [ReportsController::class, 'chartData'])->name('chart.data');
        Route::get('/export', [ReportsController::class, 'exportReport'])->name('export');
    });
    
   

    // إدارة الصلاحيات
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [PermissionController::class, 'update'])->name('permissions.update');
     
    // إدارة الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('deleteAllRead');
    });
});

    // إدارة الانتخابات
    Route::prefix("elections")->name("elections.")->group(function () {
        Route::get("/",[SuperAdminController::class, "indexElections"])->name("index");
       // Route::get("/search",[SuperAdminController::class, "searchElections"])->name("search");
        Route::get("/create",[SuperAdminController::class, "createElection"])->name("create");
        Route::post("/",[SuperAdminController::class, "storeElection"])->name("store");
        Route::get("/export",[SuperAdminController::class, "exportElections"])->name("export");
        Route::post("/bulk-action",[SuperAdminController::class, "bulkActionElections"])->name("bulk-action");
        Route::post("/{election}/change-status",[SuperAdminController::class, "changeElectionStatus"])->name("change-status");
        Route::get("/{election}",[SuperAdminController::class, "showElection"])->name("show");
        Route::get("/{election}/edit",[SuperAdminController::class, "editElection"])->name("edit");
        Route::put("/{election}",[SuperAdminController::class, "updateElection"])->name("update");
        Route::delete("/{election}",[SuperAdminController::class, "destroyElection"])->name("destroy");
        // روتات المرشحين والنتائج داخل الانتخابات
        Route::get("/{election}/candidates",[SuperAdminController::class, "indexCandidates"])->name("candidates");
        Route::get("/{election}/results",[SuperAdminController::class, "showResults"])->name("results");
    });