<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Assistant;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Notification;
use App\Models\SuperAdmin;
use App\Models\Voter;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    // Authentication
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $guard = $request->input('guard', 'voter'); // voter, admin, super_admin, assistant

        config(['auth.defaults.guard' => $guard]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }


    // Elections
    public function getElections()
    {
        return Election::all();
    }

    public function getElection($id)
    {
        return Election::with('candidates')->findOrFail($id);
    }

    // Voting
    public function castVote(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $voter = $request->user();

        // Check if voter has already voted in this election
        $existingVote = Vote::where('voter_id', $voter->id)
            ->where('election_id', $request->election_id)
            ->exists();

        if ($existingVote) {
            return response()->json(['message' => 'You have already voted in this election.'], 403);
        }

        $vote = Vote::create([
            'voter_id' => $voter->id,
            'election_id' => $request->election_id,
            'candidate_id' => $request->candidate_id,
        ]);

        return response()->json(['message' => 'Vote cast successfully.', 'vote' => $vote]);
    }


    // Generic resource methods (example for Voters)
    public function getVoters()
    {
        return Voter::all();
    }

    public function getVoter($id)
    {
        return Voter::findOrFail($id);
    }

    public function createVoter(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters',
            'password' => 'required|string|min:8',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $voter = Voter::create($validatedData);

        return response()->json($voter, 201);
    }

    public function updateVoter(Request $request, $id)
    {
        $voter = Voter::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:voters,email,' . $voter->id,
        ]);

        $voter->update($validatedData);

        return response()->json($voter);
    }

    public function deleteVoter($id)
    {
        $voter = Voter::findOrFail($id);
        $voter->delete();

        return response()->json(null, 204);
    }

}
