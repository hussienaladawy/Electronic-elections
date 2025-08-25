<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Exports\VotersExport;
use App\Imports\VotersImport;
use Maatwebsite\Excel\Facades\Excel;

class AssistantController extends Controller
{
    public function dashboard()
    {
        $assistant = auth('assistant')->user();
        $notifications = $assistant->unreadNotifications; // Or ->notifications for all
        
        $stats = [
            'voters_count'     => Voter::count(),
            'candidates_count' => Candidate::count(),
            'available_elections_count' => Election::where('status', 'active')->count(),
        ];

        $recentElections = Election::where('status', 'active')
                                    ->orderBy('start_date', 'desc')
                                    ->limit(5)
                                    ->get();

        return view('assistant.dashboard', compact('stats', 'recentElections', 'notifications'));
    }

    public function showVoterImportForm()
    {
        return view('assistant.voters.import');
    }

    public function importVoters(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new VotersImport, $request->file('file'));

        return redirect()->route('assistant.dashboard')->with('success', 'تم استيراد الناخبين بنجاح.');
    }

    public function exportVoters(Request $request)
    {
        return Excel::download(new VotersExport, 'voters.xlsx');
    }

    public function indexElections()
    {
        $elections = Election::all();
        return view('assistant.elections.index', compact('elections'));
    }

    public function indexCandidates(Election $election)
    {
        $candidates = $election->candidates;
        return view('assistant.elections.candidates.index', compact('election', 'candidates'));
    }

    public function createCandidate(Election $election)
    {
        return view('assistant.elections.candidates.create', compact('election'));
    }

    public function storeCandidate(Request $request, Election $election)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $election->candidates()->create($request->all());

        return redirect()->route('assistant.elections.candidates.index', $election)
            ->with('success', 'تم إضافة المرشح بنجاح.');
    }

    public function editCandidate(Election $election, Candidate $candidate)
    {
        return view('assistant.elections.candidates.edit', compact('election', 'candidate'));
    }

    public function updateCandidate(Request $request, Election $election, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $candidate->update($request->all());

        return redirect()->route('assistant.elections.candidates.index', $election)
            ->with('success', 'تم تعديل بيانات المرشح بنجاح.');
    }
}