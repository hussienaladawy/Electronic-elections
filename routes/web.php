<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\IntegratedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome')->name('home');
// });
// عرض فورم رفع الملف
Route::get('/voters/import', [VoterController::class, 'showImportForm'])->name('voters.import.form');

// تنفيذ عملية الاستيراد
Route::post('/voters/import', [VoterController::class, 'import'])->name('voters.import');
Route::post('/voters/import', [VoterController::class, 'import'])
    ->name('voters.import.store');


    
Route::get('/', [IntegratedController::class, 'welcome'])->name('home');



Route::get('/test-session/super_admin', function () {
    return auth('super_admin')->user();
});

Route::get('/test-session/admin', function () {
    return auth('admin')->user();
});

Route::get('/test-session/voter', function () {
    return auth('voter')->user();
});
Route::get('/test-session/assistant', function () {
    return auth('assistant')->user();
});

require __DIR__.'/public.php';
require __DIR__.'/super_admin.php';
require __DIR__.'/admin.php';
require __DIR__.'/assistant.php';
require __DIR__.'/voter.php';
require __DIR__.'/web_final.php';
// require __DIR__.'/permissions.php';

