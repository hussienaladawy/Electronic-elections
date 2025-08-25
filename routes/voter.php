<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\NotificationController;

// Public routes for voter registration
Route::prefix('voter')->name('voter.')->group(function () {
    
    Route::get('/register', function () {
        return view('auth.register'); // Changed to auth.register to match convention
    })->name('register');

    Route::post('/register', function (Illuminate\Http\Request $request) {
        $validator = Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:voters',
            'national_id' => 'required|string|unique:voters',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'province' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $voter = App\Models\Voter::create([
            'name' => $request->name,
            'email' => $request->email,
            'national_id' => $request->national_id,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'province' => $request->province,
            'password' => bcrypt($request->password),
            'status' => true
        ]);
        
        // Log the user in
        auth('voter')->login($voter);

        return redirect()->route('voter.dashboard')->with('success', 'تم تسجيلك بنجاح');
    })->name('register.submit');
});


// Protected routes for authenticated voters
Route::prefix('voter')->middleware(['auth:voter'])->name('voter.')->group(function () {
    
    Route::get("/dashboard", [VoterController::class, "dashboard"])->name("dashboard");
    
    Route::get("/profile", [VoterController::class,"profile"])->name("profile");
    Route::put("/profile", [VoterController::class,"updateProfile"])->name("profile.update");

    Route::prefix("elections")->name("elections.")->group(function () {
        Route::get("/", [VotingController::class, "availableElections"])->name("index");
    });

    Route::prefix("votes")->name("votes.")->group(function () {
        Route::get("/history", [VoterController::class, 'votesHistory'])->name("history");
        Route::get("/verify/{election}", function () { return view("voter.votes.verify"); })->name("verify");
    });

    Route::prefix("notifications")->name("notifications.")->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}', [NotificationController::class, 'markAsRead'])->name('show');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('deleteAllRead');
    });

    Route::prefix("help")->name("help.")->group(function () {
        Route::get("/", function () { return view("voter.help"); })->name("index");
    });
});