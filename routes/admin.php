<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Voters
    Route::prefix('voters')->name('voters.')->middleware(["auth:admin",'can:manage voters'])->group(function () {
        Route::get('/', [AdminController::class, 'indexVoters'])->name('index');
        Route::get('/send-password-reset', [SuperAdminController::class,'sendPasswordResetMail'])->name('send-password-reset');
        Route::get('/send-message', [SuperAdminController::class,'sendMessageMail'])->name('send-message');
        Route::get('/send-welcome-email', [SuperAdminController::class,'sendWelcomeMail'])->name('send-welcome-email');
        Route::get('/create', [AdminController::class, 'createVoter'])->name('create');
        Route::post('/', [AdminController::class, 'storeVoter'])->name('store');
        Route::get('/{voter}/edit', [AdminController::class, 'editVoter'])->name('edit');
        Route::put('/{voter}', [AdminController::class, 'updateVoter'])->name('update');
        Route::delete('/{voter}', [AdminController::class, 'destroyVoter'])->name('destroy');
        Route::get('/{voter}', [AdminController::class, 'showVoter'])->name('show');
        Route::post('/{voter}/toggle-status', [AdminController::class, 'toggleVoterStatus'])->name('toggle-status');
        Route::get('/export', [AdminController::class, 'exportVoters'])->name('export');
        Route::post('/bulk-action', [AdminController::class, 'bulkActionVoters'])->name('bulk-action');
    });
    // Elections
    Route::prefix('elections')->name('elections.')->middleware(['auth:admin','can:manage elections'])->group(function () {
        Route::get('/', [AdminController::class, 'indexElections'])->name('index');
        Route::get('/create', [AdminController::class, 'createElection'])->name('create');
        Route::post('/', [AdminController::class, 'storeElection'])->name('store');
        Route::get('/{election}/edit', [AdminController::class, 'editElection'])->name('edit');
        Route::put('/{election}', [AdminController::class, 'updateElection'])->name('update');
        Route::delete('/{election}', [AdminController::class, 'destroyElection'])->name('destroy');
        Route::get('/{election}', [AdminController::class, 'showElection'])->name('show');
        Route::post('/{election}/change-status', [AdminController::class, 'changeElectionStatus'])->name('change-status');
        Route::get('/{election}/candidates', [AdminController::class, 'indexCandidates'])->name('candidates');
        Route::get('/{election}/results', [AdminController::class, 'showResults'])->name('results');
          Route::prefix("candidates")->name("candidates.")->group(function () {
        Route::get("/", [AdminController::class, "indexCandidates"])->name("index");
        Route::get("/create/{election}", [AdminController::class, "createCandidate"])->name("create");
        Route::post("/{election}", [AdminController::class, "storeCandidate"])->name("store");
        Route::get("/{candidate}/edit", [AdminController::class, "editCandidate"])->name("edit");
        Route::put("/{candidate}", [AdminController::class, "updateCandidate"])->name("update");
        Route::delete("/{candidate}", [AdminController::class, "destroyCandidate"])->name("destroy");
        Route::get("/{candidate}", [AdminController::class, "showCandidate"])->name("show");
    });
    });

    // Admins
    Route::prefix('admins')->name('admins.')->middleware(["auth:admin",'can:manage admins'])->group(function () {
        Route::get('/', [AdminController::class, 'indexAdmins'])->name('index');
        Route::get('/create', [AdminController::class, 'createAdmin'])->name('create');
        Route::post('/', [AdminController::class, 'storeAdmin'])->name('store');
        Route::get('/{admin}/edit', [AdminController::class, 'editAdmin'])->name('edit');
        Route::put('/{admin}', [AdminController::class, 'updateAdmin'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroyAdmin'])->name('destroy');
        Route::get('/{admin}', [AdminController::class, 'showAdmin'])->name('show');
        Route::post('/{admin}/toggle-status', [AdminController::class, 'toggleAdminStatus'])->name('toggle-status');
        Route::get('/export', [AdminController::class, 'exportAdmins'])->name('export');
        Route::post('/bulk-action', [AdminController::class, 'bulkActionAdmins'])->name('bulk-action');
    });
});








    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminController::class, 'indexReports'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'dashboardReports'])->name('dashboard');
        Route::get('/election/{election}/results', [AdminController::class, 'electionResultsReports'])->name('election.results');
    });

Route::prefix("admin")->name("admin.")->middleware(["auth:admin", "can:manage assistants"])->group(function () {
    Route::prefix("assistants")->name("assistants.")->group(function () {
    Route::get("/export", [AdminController::class,"exportAssistants"])->name("export");
        Route::get("/", [AdminController::class, "indexAssistants"])->name("index");
        Route::get("/create", [AdminController::class, "createAssistant"])->name("create");
        Route::post("/", [AdminController::class, "storeAssistant"])->name("store");
        Route::get("/{assistant}/edit", [AdminController::class, "editAssistant"])->name("edit");
        Route::put("/{assistant}", [AdminController::class, "updateAssistant"])->name("update");
        Route::delete("/{assistant}", [AdminController::class, "destroyAssistant"])->name("destroy");
        Route::get("/{assistant}", [AdminController::class, "showAssistant"])->name("show");
        Route::post("/{assistant}/toggle-status", [AdminController::class, "toggleAssistantStatus"])->name("toggle-status");
        Route::get("/export", [AdminController::class, "exportAssistants"])->name("export");
        Route::post("/bulk-action", [AdminController::class, "bulkActionAssistants"])->name("bulk-action");
    });
});





