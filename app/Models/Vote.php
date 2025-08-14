<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'candidate_id',
        'voter_id',
        'vote_hash',
        'encrypted_vote',
        'ip_address',
        'user_agent',
        'verification_code',
        'is_verified',
        'voted_at',
        'vote_code',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'voted_at' => 'datetime'
    ];

    protected $hidden = [
        'voter_id',
        'encrypted_vote',
        'ip_address',
        'user_agent'
    ];

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vote) {
            $vote->vote_hash = Str::random(64);
            $vote->verification_code = Str::random(16);
            $vote->voted_at = now();
        });
    }

    // العلاقات
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }

    // الدوال المساعدة
    public function isVerified()
    {
        return $this->is_verified;
    }

    public function getStatusTextAttribute()
    {
        return $this->is_verified ? 'مُتحقق منه' : 'في انتظار التحقق';
    }

    public function verify()
    {
        $this->update(['is_verified' => true]);
        return $this;
    }

    // تشفير الصوت
    public function encryptVote($candidateId, $secretKey)
    {
        $voteData = [
            'candidate_id' => $candidateId,
            'timestamp' => now()->timestamp,
            'random' => Str::random(32)
        ];

        $this->encrypted_vote = encrypt(json_encode($voteData));
        return $this;
    }

    // فك تشفير الصوت (للتحقق فقط)
    public function decryptVote()
    {
        try {
            return json_decode(decrypt($this->encrypted_vote), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    // التحقق من صحة الصوت
    public function validateVote()
    {
        $decryptedVote = $this->decryptVote();
        
        if (!$decryptedVote) {
            return false;
        }

        return $decryptedVote['candidate_id'] == $this->candidate_id;
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function scopeForCandidate($query, $candidateId)
    {
        return $query->where('candidate_id', $candidateId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('voted_at', today());
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('voted_at', [$startDate, $endDate]);
    }
}

