<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VotingController;

// روتات التصويت العامة
Route::prefix("public")->name("public.")->group(function () {
    Route::get("/elections", [VotingController::class, "availableElections"])->name("elections");
    Route::get("/verify-vote", [VotingController::class, "verifyVote"])->name("verify_vote");
    Route::get("/results/{election}", [VotingController::class, "showResults"])->name("results"); // Added public results route
    Route::get("/verify", [VotingController::class, "verifyVote"])->name("verify"); // Added public verify route (using existing verifyVote function)
});

Route::get("/elections", [VotingController::class, "availableElections"])->name("elections");
Route::get("/verify-vote", [VotingController::class,'verifyVote'])->name("verify_vote");
//Route::get("public/results/{election}", [VotingController::class,'showResults'])->name("public.idex");

Route::get("/verify-vote", [VotingController::class, "verifyVote"])->name("verify_vote");
Route::get("/results/{election}", [VotingController::class, "showResults"])->name("results");
 Route::get("/verify", [VotingController::class, "verifyVote"])->name("verify");




