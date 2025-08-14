<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Admin;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Assistant;
use App\Models\SuperAdmin;

use App\Models\Notification;

use Illuminate\Http\Request;
use App\Imports\VotersImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\NotificationRecipient;

class VoterController extends Controller
{
    public function dashboard()
    {
        $voter = Auth::user();

        // Get IDs of elections the voter has participated in
        $votedElectionsIds = Vote::where('voter_id', $voter->id)
                                ->pluck('election_id')
                                ->unique();

        // My Votes
        $myVotesCount = $votedElectionsIds->count();

        // Available Elections
        $availableElectionsCount = Election::where('status', 'active')
            ->where('start_date', '<', now())
            ->where('end_date', '>', now())
            ->whereNotIn('id', $votedElectionsIds->toArray())
            ->count();
            
        // Pending Elections
        $pendingElectionsCount = Election::where('status', 'pending')->count();

        // New Notifications
        $newNotificationsCount = NotificationRecipient::where("recipient_id", $voter->id)
                                                ->where("recipient_type", "voter")
                                                ->whereNull("read_at")
                                                ->count();

        $stats = [
            'my_votes' => $myVotesCount,
            'available_elections' => $availableElectionsCount,
            'pending_elections' => $pendingElectionsCount,
            'new_notifications' => $newNotificationsCount,
        ];

        // Keep existing queries for other parts of the dashboard
        $recentElections = Election::where("status", "active")
                                    ->orWhere("status", "closed")
                                    ->orderBy("end_date", "desc")
                                    ->limit(5)
                                    ->get();
        
        // This variable is now for the view check, it contains only IDs
        $votedElections = $votedElectionsIds;

        $upcomingElections = Election::where("status", "pending")
                                    ->orderBy("start_date", "asc")
                                    ->limit(5)
                                    ->get();

        $recentNotifications = NotificationRecipient::where("recipient_id", $voter->id)
                                            ->where("recipient_type", "voter")
                                            ->with("notification")
                                            ->orderBy("created_at", "desc")
                                            ->limit(5)
                                            ->get();

        return view("voter.dashboard", compact(
            "stats", 
            "recentElections", 
            "votedElections", // This is now a collection of IDs
            "upcomingElections", 
            "recentNotifications"
        ));
    }

    public function votesHistory()
    {
        $user = Auth::user();
        $votedElections = Vote::where("voter_id", $user->id)
                                ->with(["election", "candidate"])
                                ->orderBy("created_at", "desc")
                                ->get();

        return view("voter.votes.history", compact("votedElections"));
    }
    public function showImportForm()
{
    return view('voter.import');
}

   

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new VotersImport, $request->file('file'));

    return redirect()->back()->with('success', 'تم استيراد الناخبين بنجاح');
}

public function profile(Request $request){
    $voter = Auth::user();
    return view('voter.profile.index', compact('voter'));
}

public function updateProfile(Request $request)
    {
        $voter = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters,email,' . $voter->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $voter->update($updateData);

        return redirect()->route('voter.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

}