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
        Schema::dropIfExists('voters');
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('national_id')->unique();
            $table->date('birth_date')->nullable(); // تاريخ الميلاد
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->default('عنوان غير معروف');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->unsignedBigInteger('voting_center_id')->nullable();
            $table->boolean('is_eligible')->default(true);
            $table->boolean('has_voted')->default(false);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('super_admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('super_admins')->onDelete('set null');

            // Indexes
            $table->index(['email', 'status']);
            $table->index('national_id');
            $table->index('phone');
            $table->index(['city', 'district']);
            $table->index('voting_center_id');
            $table->index(['is_eligible', 'has_voted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};

