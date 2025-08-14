<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('voter_id');
            $table->string('vote_hash', 64)->unique();
            $table->text('encrypted_vote');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('verification_code', 16);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('voted_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('voter_id')->references('id')->on('voters')->onDelete('cascade');

            // Indexes
            $table->index(['election_id', 'candidate_id']);
            $table->index(['election_id', 'voter_id']);
            $table->index(['election_id', 'voted_at']);
            $table->index('is_verified');
            $table->index('voted_at');
            $table->index('verification_code');

            // Unique constraint to prevent double voting
            $table->unique(['election_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};

