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
        Schema::create('assistants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('national_id')->unique();
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->string('work_area')->nullable();
            $table->string('shift_time')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('super_admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('super_admins')->onDelete('set null');

            // Indexes
            $table->index(['email', 'status']);
            $table->index('national_id');
            $table->index('phone');
            $table->index('assigned_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistants');
    }
};

