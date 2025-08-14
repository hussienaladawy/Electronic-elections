<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vote;
use App\Models\Voter;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VotingController extends Controller
{
    /**
     * عرض قائمة الانتخابات المتاحة للتصويت
     */
    public function availableElections()
    {
        
        $elections = Election::active()
            ->get();

        return view("voting.elections", compact("elections"));
    }

    /**
     * عرض صفحة التصويت لانتخابات محددة
     */
    public function showVotingPage(Election $election)
    {
        
        // التحقق من أن الانتخابات مفتوحة للتصويت
        if (!$election->isVotingOpen()) {
            return redirect()->route("voting.elections")
                ->with("error", "الانتخابات غير متاحة للتصويت حالياً");
        }

        // التحقق من أن المستخدم لم يصوت من قبل
        if (auth("voter")->check()) {
            $hasVoted = Vote::where("election_id", $election->id)
                ->where("voter_id", auth("voter")->id())
                ->exists();

            if ($hasVoted) {
                return redirect()->route("voting.elections")
                    ->with("error", "لقد قمت بالتصويت في هذه الانتخابات من قبل");
            }
        }

        $candidates = $election->candidates()
            ->active()
            ->orderBy("order_number")
            ->get();

        return view("voting.vote", compact("election", "candidates"));
    }

    /**
     * معالجة عملية التصويت
     */
    public function submitVote(Request $request, Election $election)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            "candidate_id" => "required|exists:candidates,id",
            "voter_password" => "required|string"
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // التحقق من أن الانتخابات مفتوحة
        if (!$election->isVotingOpen()) {
            return redirect()->back()
                ->with("error", "الانتخابات غير متاحة للتصويت حالياً");
        }

        // التحقق من المرشح
        $candidate = Candidate::where("id", $request->candidate_id)
            ->where("election_id", $election->id)
            ->where("status", true)
            ->first();

        if (!$candidate) {
            return redirect()->back()
                ->with("error", "المرشح المحدد غير صحيح");
        }

        // التحقق من هوية الناخب
        $voter = null;
        if (auth("voter")->check()) {
            $voter = auth("voter")->user();
            
            // التحقق من كلمة المرور
            if (!Hash::check($request->voter_password, $voter->password)) {
                return redirect()->back()
                    ->with("error", "كلمة المرور غير صحيحة");
            }
        } else {
            return redirect()->route("voter.login")
                ->with("error", "يجب تسجيل الدخول أولاً");
        }

        // التحقق من أن الناخب لم يصوت من قبل
        $existingVote = Vote::where("election_id", $election->id)
            ->where("voter_id", $voter->id)
            ->first();

        if ($existingVote) {
            return redirect()->route("voting.elections")
                ->with("error", "لقد قمت بالتصويت في هذه الانتخابات من قبل");
        }

        try {
            DB::beginTransaction();

            // إنشاء الصوت
            $vote = new Vote([
                "election_id" => $election->id,
                "candidate_id" => $candidate->id,
                "voter_id" => $voter->id,
                "ip_address" => $request->ip(),
                "user_agent" => $request->userAgent(),
                "is_verified" => true
            ]);
              // توليد كود التصويت العشوائي
               $vote->vote_code = Str::random(64);

            // تشفير الصوت
            $vote->encryptVote($candidate->id, config("app.key"));
            $vote->save();

            // تسجيل العملية في السجلات
            Log::info("Vote submitted", [
                "election_id" => $election->id,
                "candidate_id" => $candidate->id,
                "voter_id" => $voter->id,
                "vote_hash" => $vote->vote_hash,
                "ip_address" => $request->ip()
            ]);

            DB::commit();

            return redirect()->route("voting.confirmation", $vote->vote_hash)
                ->with("success", "تم تسجيل صوتك بنجاح");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Vote submission failed", [
                "election_id" => $election->id,
                "voter_id" => $voter->id,
                "error" => $e->getMessage()
            ]);

            return redirect()->back()
                ->with("error", "حدث خطأ أثناء تسجيل الصوت. يرجى المحاولة مرة أخرى");
        }
    }

    /**
     * عرض صفحة تأكيد التصويت
     */
    public function showConfirmation($voteHash)
    {
        $vote = Vote::where("vote_hash", $voteHash)
            ->with(["election", "candidate"])
            ->first();

        if (!$vote) {
            return redirect()->route("voting.elections")
                ->with("error", "رمز التحقق غير صحيح");
        }

        // التحقق من أن الصوت يخص المستخدم الحالي
        if (auth("voter")->id() !== $vote->voter_id) {
            return redirect()->route("voting.elections")
                ->with("error", "غير مصرح لك بعرض هذه الصفحة");
        }

        return view("voting.confirmation", compact("vote"));
    }

    /**
     * التحقق من صحة الصوت وعرضه في صفحة عامة
     */
    public function verifyVote(Request $request)
    {
        $vote = null;
        if ($request->has("vote_code")) {
            $vote = Vote::where("vote_code", $request->input("vote_code"))
                        ->with(["voter", "election", "candidate"])
                        ->first();
        }
        
        return view("public.verify_vote", compact("vote"));
    }

    /**
     * عرض النتائج الأولية (للانتخابات المكتملة فقط)
     */
    public function showResults(Election $election)
    {
      
        // التحقق من أن الانتخابات انتهت
        if ($election->status !== "completed" && !$election->end_date->isPast()) {
            return redirect()->route("voting.elections")
                ->with("error", "النتائج غير متاحة حتى انتهاء الانتخابات");
        }

        $results = DB::table("votes")
            ->join("candidates", "votes.candidate_id", "=", "candidates.id")
            ->where("votes.election_id", $election->id)
            ->where("votes.is_verified", true)
            ->select(
                "candidates.id",
                "candidates.name",
                "candidates.party_affiliation",
                "candidates.image",
                DB::raw("COUNT(votes.id) as vote_count")
            )
            ->groupBy("candidates.id", "candidates.name", "candidates.party_affiliation", "candidates.image")
            ->orderBy("vote_count", "desc")
            ->get();

        $totalVotes = $results->sum("vote_count");

        // حساب النسب المئوية
        $results = $results->map(function ($result) use ($totalVotes) {
            $result->percentage = $totalVotes > 0 ? round(($result->vote_count / $totalVotes) * 100, 2) : 0;
            return $result;
        });

        return view("voting.results", compact("election", "results", "totalVotes"));
    }

    /**
     * إحصائيات التصويت في الوقت الفعلي (للمسؤولين فقط)
     */
    public function liveStats(Election $election)
    {
        // التحقق من الصلاحيات
        if (!auth("super_admin")->check() && !auth("admin")->check()) {
            abort(403, "غير مصرح لك بعرض هذه الصفحة");
        }

        $stats = [
            "total_votes" => Vote::where("election_id", $election->id)->count(),
            "verified_votes" => Vote::where("election_id", $election->id)->where("is_verified", true)->count(),
            "hourly_votes" => Vote::where("election_id", $election->id)
                ->where("voted_at", ">=", Carbon::now()->subHour())
                ->count(),
            "candidate_votes" => DB::table("votes")
                ->join("candidates", "votes.candidate_id", "=", "candidates.id")
                ->where("votes.election_id", $election->id)
                ->where("votes.is_verified", true)
                ->select("candidates.name", DB::raw("COUNT(votes.id) as count"))
                ->groupBy("candidates.id", "candidates.name")
                ->orderBy("count", "desc")
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * تصدير النتائج (للمسؤولين فقط)
     */
    public function exportResults(Election $election, $format = "csv")
    {
        // التحقق من الصلاحيات
        if (!auth("super_admin")->check() && !auth("admin")->check()) {
            abort(403, "غير مصرح لك بتصدير النتائج");
        }

        $results = DB::table("votes")
            ->join("candidates", "votes.candidate_id", "=", "candidates.id")
            ->where("votes.election_id", $election->id)
            ->where("votes.is_verified", true)
            ->select(
                "candidates.name as candidate_name",
                "candidates.party_affiliation",
                DB::raw("COUNT(votes.id) as vote_count")
            )
            ->groupBy("candidates.id", "candidates.name", "candidates.party_affiliation")
            ->orderBy("vote_count", "desc")
            ->get();

        if ($format === "csv") {
            $filename = "election_results_{$election->id}_" . date("Y-m-d_H-i-s") . ".csv";
            
            $headers = [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($results) {
                $file = fopen("php://output", "w");
                fputcsv($file, ["اسم المرشح", "الانتماء الحزبي", "عدد الأصوات"]);
                
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->candidate_name,
                        $result->party_affiliation ?: "مستقل",
                        $result->vote_count
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($results);
    }
}
