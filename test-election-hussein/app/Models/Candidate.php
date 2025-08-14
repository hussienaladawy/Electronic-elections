<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'biography',
        'party_affiliation',
        'program',
        'image',
        'order_number',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'program' => 'array',
        'status' => 'boolean'
    ];

    // العلاقات
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'updated_by');
    }

    // الدوال المساعدة
    public function isActive()
    {
        return $this->status == 1;
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'نشط' : 'غير نشط';
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }

    public function getVotePercentageAttribute()
    {
        $totalElectionVotes = $this->election->total_votes;
        if ($totalElectionVotes == 0) {
            return 0;
        }
        return round(($this->total_votes / $totalElectionVotes) * 100, 2);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/candidates/' . $this->image);
        }
        return asset('images/default-candidate.png');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    public function scopeOrderedByNumber($query)
    {
        return $query->orderBy('order_number');
    }
}

