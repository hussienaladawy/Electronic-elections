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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->string('name');
            $table->text('biography')->nullable();
            $table->string('party_affiliation')->nullable();
            $table->json('program')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('super_admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('super_admins')->onDelete('set null');

            // Indexes
            $table->index(['election_id', 'status']);
            $table->index(['election_id', 'order_number']);
            $table->index('order_number');
            $table->unique(['election_id', 'order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};

