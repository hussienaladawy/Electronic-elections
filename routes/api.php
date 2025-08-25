<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Elections
    Route::get('/elections', [ApiController::class, 'getElections']);
    Route::get('/elections/{id}', [ApiController::class, 'getElection']);

    // Voting
    Route::post('/votes', [ApiController::class, 'castVote']);

    // Voters
    Route::apiResource('voters', ApiController::class)->except(['store', 'update', 'destroy']);
    Route::post('voters', [ApiController::class, 'createVoter']);
    Route::put('voters/{id}', [ApiController::class, 'updateVoter']);
    Route::delete('voters/{id}', [ApiController::class, 'deleteVoter']);
});


// مجموعة روتات API للسوبرادمن
Route::prefix('super-admin')->name('api.super_admin.')->group(function () {
    
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // البحث
    Route::get('/search', [SuperAdminController::class, 'search'])->name('search');
    
    // إدارة السوبرادمن
    Route::apiResource('super-admins', SuperAdminController::class, [
        'names' => [
            'index' => 'super_admins.index',
            'store' => 'super_admins.store',
            'show' => 'super_admins.show',
            'update' => 'super_admins.update',
            'destroy' => 'super_admins.destroy',
        ]
    ]);
    
    // إدارة الادمن
    Route::apiResource('admins', SuperAdminController::class, [
        'names' => [
            'index' => 'admins.index',
            'store' => 'admins.store',
            'show' => 'admins.show',
            'update' => 'admins.update',
            'destroy' => 'admins.destroy',
        ]
    ]);
    
    // إدارة المساعدين
    Route::apiResource('assistants', SuperAdminController::class, [
        'names' => [
            'index' => 'assistants.index',
            'store' => 'assistants.store',
            'show' => 'assistants.show',
            'update' => 'assistants.update',
            'destroy' => 'assistants.destroy',
        ]
    ]);
    
    // إدارة الناخبين
    Route::apiResource('voters', SuperAdminController::class, [
        'names' => [
            'index' => 'voters.index',
            'store' => 'voters.store',
            'show' => 'voters.show',
            'update' => 'voters.update',
            'destroy' => 'voters.destroy',
        ]
    ]);
});

