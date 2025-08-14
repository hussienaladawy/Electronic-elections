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
        Schema::create('super_admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('national_id')->unique();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('super_admins')) {
            // Drop foreign keys only if they exist
            Schema::table('super_admins', function (Blueprint $table) {
                if (Schema::hasColumn('super_admins', 'created_by')) {
                    DB::statement('ALTER TABLE super_admins DROP FOREIGN KEY IF EXISTS super_admins_created_by_foreign');
                }
                if (Schema::hasColumn('super_admins', 'updated_by')) {
                    DB::statement('ALTER TABLE super_admins DROP FOREIGN KEY IF EXISTS super_admins_updated_by_foreign');
                }
            });
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('super_admins');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

