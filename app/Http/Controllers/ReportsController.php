<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * عرض لوحة التقارير الرئيسية
     */
    public function dashboard()
    {
        $stats = [
            'total_elections' => Election::count(),
            'active_elections' => Election::where('status', 'active')->count(),
            'total_votes' => Vote::where('is_verified', true)->count(),
            'total_voters' => Voter::where('status', true)->count(),
        ];

        $recentElections = Election::with(['candidates', 'votes'])
            ->withCount(['candidates', 'votes'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('super_admin.reports.dashboard', compact('stats', 'recentElections'));
    }

    /**
     * تقرير نتائج انتخابات محددة
     */
    public function electionResults(Election $election)
    {
        // إحصائيات عامة
        $totalVotes = $election->votes()->where('is_verified', true)->count();
        $totalCandidates = $election->candidates()->where('status', true)->count();
        
        // نتائج المرشحين
        $candidateResults = DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->where('votes.election_id', $election->id)
            ->where('votes.is_verified', true)
            ->select(
                'candidates.id',
                'candidates.name',
                'candidates.party_affiliation',
                'candidates.image',
                'candidates.order_number',
                DB::raw('COUNT(votes.id) as vote_count'),
                DB::raw('ROUND((COUNT(votes.id) * 100.0 / ' . max($totalVotes, 1) . '), 2) as percentage')
            )
            ->groupBy('candidates.id', 'candidates.name', 'candidates.party_affiliation', 'candidates.image', 'candidates.order_number')
            ->orderBy('vote_count', 'desc')
            ->get();

        // توزيع الأصوات حسب الوقت
        $hourlyVotes = DB::table('votes')
            ->where('election_id', $election->id)
            ->where('is_verified', true)
            ->select(
                DB::raw('HOUR(voted_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('HOUR(voted_at)'))
            ->orderBy('hour')
            ->get();

        // توزيع الأصوات حسب الفئات العمرية
        $ageGroupVotes = DB::table('votes')
            ->join('voters', 'votes.voter_id', '=', 'voters.id')
            ->where('votes.election_id', $election->id)
            ->where('votes.is_verified', true)
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 25 THEN "18-24"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 35 THEN "25-34"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 45 THEN "35-44"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 55 THEN "45-54"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 65 THEN "55-64"
                    ELSE "65+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        // توزيع الأصوات حسب الجنس
        $genderVotes = DB::table('votes')
            ->join('voters', 'votes.voter_id', '=', 'voters.id')
            ->where('votes.election_id', $election->id)
            ->where('votes.is_verified', true)
            ->select(
                'voters.gender',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('voters.gender')
            ->get();

        return view('super_admin.reports.election_results', compact(
            'election', 
            'totalVotes', 
            'totalCandidates', 
            'candidateResults', 
            'hourlyVotes', 
            'ageGroupVotes', 
            'genderVotes'
        ));
    }

    /**
     * تقرير مقارنة الانتخابات
     */
    public function compareElections(Request $request)
    {
        $elections = Election::where('status', '!=', 'draft')
            ->orderBy('start_date', 'desc')
            ->get();

        $selectedElections = [];
        $comparisonData = [];

        if ($request->has('election_ids') && is_array($request->election_ids)) {
            $selectedElections = Election::whereIn('id', $request->election_ids)
                ->with(['candidates', 'votes'])
                ->get();

            foreach ($selectedElections as $election) {
                $totalVotes = $election->votes()->where('is_verified', true)->count();
                $totalCandidates = $election->candidates()->where('status', true)->count();
                
                $comparisonData[] = [
                    'election' => $election,
                    'total_votes' => $totalVotes,
                    'total_candidates' => $totalCandidates,
                    'participation_rate' => $totalVotes > 0 ? round(($totalVotes / max($election->registered_voters ?? 1, 1)) * 100, 2) : 0,
                    'winner' => $election->candidates()
                        ->withCount(['votes' => function($query) {
                            $query->where('is_verified', true);
                        }])
                        ->orderBy('votes_count', 'desc')
                        ->first()
                ];
            }
        }

        return view('super_admin.reports.compare_elections', compact(
            'elections', 
            'selectedElections', 
            'comparisonData'
        ));
    }

    /**
     * تقرير إحصائيات الناخبين
     */
    public function voterStatistics()
    {
        // إحصائيات عامة
        $totalVoters = Voter::where('status', true)->count();
        $activeVoters = Voter::whereHas('votes', function($query) {
            $query->where('is_verified', true);
        })->count();

        // توزيع الناخبين حسب الفئات العمرية
        $ageGroups = DB::table('voters')
            ->where('status', true)
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 25 THEN "18-24"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 35 THEN "25-34"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 45 THEN "35-44"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 55 THEN "45-54"
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 65 THEN "55-64"
                    ELSE "65+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        // توزيع الناخبين حسب الجنس
        $genderDistribution = DB::table('voters')
            ->where('status', true)
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get();

        // توزيع الناخبين حسب المحافظة
        $provinceDistribution = DB::table('voters')
            ->where('status', true)
            ->select('province', DB::raw('COUNT(*) as count'))
            ->groupBy('province')
            ->orderBy('count', 'desc')
            ->get();

        // معدلات المشاركة حسب الفئات العمرية
        $participationByAge = DB::table('voters')
            ->leftJoin('votes', function($join) {
                $join->on('voters.id', '=', 'votes.voter_id')
                     ->where('votes.is_verified', true);
            })
            ->where('voters.status', true)
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 25 THEN "18-24"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 35 THEN "25-34"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 45 THEN "35-44"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 55 THEN "45-54"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 65 THEN "55-64"
                    ELSE "65+"
                END as age_group'),
                DB::raw('COUNT(voters.id) as total_voters'),
                DB::raw('COUNT(votes.id) as active_voters'),
                DB::raw('ROUND((COUNT(votes.id) * 100.0 / COUNT(voters.id)), 2) as participation_rate')
            )
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        return view('super_admin.reports.voter_statistics', compact(
            'totalVoters',
            'activeVoters',
            'ageGroups',
            'genderDistribution',
            'provinceDistribution',
            'participationByAge'
        ));
    }

    /**
     * تقرير الاتجاهات الزمنية
     */
    public function trends(Request $request)
    {
        $period = $request->get('period', '30'); // آخر 30 يوم افتراضياً
        $startDate = Carbon::now()->subDays($period);

        // اتجاه التسجيل اليومي للناخبين
        $dailyRegistrations = DB::table('voters')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // اتجاه التصويت اليومي
        $dailyVotes = DB::table('votes')
            ->where('voted_at', '>=', $startDate)
            ->where('is_verified', true)
            ->select(
                DB::raw('DATE(voted_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(voted_at)'))
            ->orderBy('date')
            ->get();

        // الانتخابات النشطة في الفترة
        $activeElections = Election::where('start_date', '>=', $startDate)
            ->orWhere('end_date', '>=', $startDate)
            ->orderBy('start_date')
            ->get();

        return view('super_admin.reports.trends', compact(
            'period',
            'startDate',
            'dailyRegistrations',
            'dailyVotes',
            'activeElections'
        ));
    }

    /**
     * API للحصول على بيانات المخططات
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type');
        $electionId = $request->get('election_id');

        switch ($type) {
            case 'candidate_votes':
                return $this->getCandidateVotesData($electionId);
            
            case 'hourly_votes':
                return $this->getHourlyVotesData($electionId);
            
            case 'age_distribution':
                return $this->getAgeDistributionData($electionId);
            
            case 'gender_distribution':
                return $this->getGenderDistributionData($electionId);
            
            case 'province_distribution':
                return $this->getProvinceDistributionData($electionId);
            
            default:
                return response()->json(['error' => 'نوع البيانات غير صحيح'], 400);
        }
    }

    /**
     * بيانات أصوات المرشحين
     */
    private function getCandidateVotesData($electionId)
    {
        $data = DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->where('votes.election_id', $electionId)
            ->where('votes.is_verified', true)
            ->select(
                'candidates.name',
                'candidates.party_affiliation',
                DB::raw('COUNT(votes.id) as votes'),
                DB::raw('COUNT(votes.id) * 100.0 / (SELECT COUNT(*) FROM votes WHERE election_id = ? AND is_verified = true) as percentage')
            )
            ->groupBy('candidates.id', 'candidates.name', 'candidates.party_affiliation')
            ->orderBy('votes', 'desc')
            ->setBindings([$electionId])
            ->get();

        return response()->json($data);
    }

    /**
     * بيانات التصويت بالساعة
     */
    private function getHourlyVotesData($electionId)
    {
        $data = DB::table('votes')
            ->where('election_id', $electionId)
            ->where('is_verified', true)
            ->select(
                DB::raw('HOUR(voted_at) as hour'),
                DB::raw('COUNT(*) as votes')
            )
            ->groupBy(DB::raw('HOUR(voted_at)'))
            ->orderBy('hour')
            ->get();

        return response()->json($data);
    }

    /**
     * بيانات توزيع الفئات العمرية
     */
    private function getAgeDistributionData($electionId)
    {
        $data = DB::table('votes')
            ->join('voters', 'votes.voter_id', '=', 'voters.id')
            ->where('votes.election_id', $electionId)
            ->where('votes.is_verified', true)
            ->select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 25 THEN "18-24"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 35 THEN "25-34"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 45 THEN "35-44"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 55 THEN "45-54"
                    WHEN TIMESTAMPDIFF(YEAR, voters.date_of_birth, CURDATE()) < 65 THEN "55-64"
                    ELSE "65+"
                END as age_group'),
                DB::raw('COUNT(*) as votes')
            )
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        return response()->json($data);
    }

    /**
     * بيانات توزيع الجنس
     */
    private function getGenderDistributionData($electionId)
    {
        $data = DB::table('votes')
            ->join('voters', 'votes.voter_id', '=', 'voters.id')
            ->where('votes.election_id', $electionId)
            ->where('votes.is_verified', true)
            ->select(
                'voters.gender',
                DB::raw('COUNT(*) as votes')
            )
            ->groupBy('voters.gender')
            ->get();

        return response()->json($data);
    }

    /**
     * بيانات توزيع المحافظات
     */
    private function getProvinceDistributionData($electionId)
    {
        $data = DB::table('votes')
            ->join('voters', 'votes.voter_id', '=', 'voters.id')
            ->where('votes.election_id', $electionId)
            ->where('votes.is_verified', true)
            ->select(
                'voters.province',
                DB::raw('COUNT(*) as votes')
            )
            ->groupBy('voters.province')
            ->orderBy('votes', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * تصدير التقرير
     */
    public function exportReport(Request $request)
    {
        $type = $request->get('type');
        $format = $request->get('format', 'csv');
        $electionId = $request->get('election_id');

        switch ($type) {
            case 'election_results':
                return $this->exportElectionResults($electionId, $format);
            
            case 'voter_statistics':
                return $this->exportVoterStatistics($format);
            
            default:
                return response()->json(['error' => 'نوع التقرير غير صحيح'], 400);
        }
    }

    /**
     * تصدير نتائج الانتخابات
     */
    private function exportElectionResults($electionId, $format)
    {
        $election = Election::findOrFail($electionId);
        
        $results = DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->where('votes.election_id', $electionId)
            ->where('votes.is_verified', true)
            ->select(
                'candidates.name as candidate_name',
                'candidates.party_affiliation',
                DB::raw('COUNT(votes.id) as vote_count'),
                DB::raw('ROUND((COUNT(votes.id) * 100.0 / (SELECT COUNT(*) FROM votes WHERE election_id = ? AND is_verified = true)), 2) as percentage')
            )
            ->groupBy('candidates.id', 'candidates.name', 'candidates.party_affiliation')
            ->orderBy('vote_count', 'desc')
            ->setBindings([$electionId])
            ->get();

        if ($format === 'csv') {
            $filename = "election_results_{$election->id}_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($results, $election) {
                $file = fopen('php://output', 'w');
                
                // إضافة BOM للدعم العربي
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // العناوين
                fputcsv($file, ['اسم المرشح', 'الانتماء الحزبي', 'عدد الأصوات', 'النسبة المئوية']);
                
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->candidate_name,
                        $result->party_affiliation ?: 'مستقل',
                        $result->vote_count,
                        $result->percentage . '%'
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json([
            'election' => $election,
            'results' => $results
        ]);
    }

    /**
     * تصدير إحصائيات الناخبين
     */
    private function exportVoterStatistics($format)
    {
        $stats = [
            'age_groups' => DB::table('voters')
                ->where('status', true)
                ->select(
                    DB::raw('CASE 
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 25 THEN "18-24"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 35 THEN "25-34"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 45 THEN "35-44"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 55 THEN "45-54"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 65 THEN "55-64"
                        ELSE "65+"
                    END as age_group'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('age_group')
                ->orderBy('age_group')
                ->get(),
            
            'gender_distribution' => DB::table('voters')
                ->where('status', true)
                ->select('gender', DB::raw('COUNT(*) as count'))
                ->groupBy('gender')
                ->get(),
            
            'province_distribution' => DB::table('voters')
                ->where('status', true)
                ->select('province', DB::raw('COUNT(*) as count'))
                ->groupBy('province')
                ->orderBy('count', 'desc')
                ->get()
        ];

        if ($format === 'csv') {
            $filename = "voter_statistics_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($stats) {
                $file = fopen('php://output', 'w');
                
                // إضافة BOM للدعم العربي
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // إحصائيات الفئات العمرية
                fputcsv($file, ['الفئة العمرية', 'العدد']);
                foreach ($stats['age_groups'] as $group) {
                    fputcsv($file, [$group->age_group, $group->count]);
                }
                
                fputcsv($file, []); // سطر فارغ
                
                // إحصائيات الجنس
                fputcsv($file, ['الجنس', 'العدد']);
                foreach ($stats['gender_distribution'] as $gender) {
                    fputcsv($file, [$gender->gender === 'male' ? 'ذكر' : 'أنثى', $gender->count]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($stats);
    }
}

