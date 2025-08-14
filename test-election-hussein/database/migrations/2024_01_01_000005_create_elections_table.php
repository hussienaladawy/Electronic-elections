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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['presidential', 'parliamentary', 'local', 'referendum'])->default('local');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_start');
            $table->datetime('registration_end');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('super_admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('super_admins')->onDelete('set null');

            // Indexes
            $table->index(['status', 'start_date']);
            $table->index(['type', 'status']);
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};

