<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\NotificationController;


// مجموعة روتات المساعدين
Route::prefix("assistant")->name("assistant.")->middleware(['auth:assistant'])->group(function () {
    Route::get('/dashboard', [AssistantController::class, 'dashboard'])->name('dashboard');

    // عرض الانتخابات
    Route::get("/elections", [AssistantController::class, "indexElections"])->name("elections.index");

    // إدارة المرشحين
    Route::prefix("elections/{election}/candidates")->name("elections.candidates.")->group(function () {
        Route::get("/", [AssistantController::class, "indexCandidates"])->name("index");
        Route::get("/create", [AssistantController::class, "createCandidate"])->name("create");
        Route::post("/", [AssistantController::class, "storeCandidate"])->name("store");
        Route::get("/{candidate}/edit", [AssistantController::class, "editCandidate"])->name("edit");
        Route::put("/{candidate}", [AssistantController::class, "updateCandidate"])->name("update");
    });

    // إدارة الناخبين
    Route::prefix('voters')->name('voters.')->group(function() {
        Route::get('/import', [AssistantController::class, 'showVoterImportForm'])->name('import');
        Route::post('/import', [AssistantController::class, 'importVoters'])->name('import.submit');
        Route::get('/export', [AssistantController::class, 'exportVoters'])->name('export');
        Route::get('/', [SuperAdminController::class, 'indexVoters'])->name('index');
        Route::get('/{voter}', [SuperAdminController::class, 'showVoter'])->name('show');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('deleteAllRead');
    });
  
});