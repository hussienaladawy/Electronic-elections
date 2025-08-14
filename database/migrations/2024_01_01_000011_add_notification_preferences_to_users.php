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
        // إضافة تفضيلات الإشعارات للناخبين
        Schema::table('voters', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable()->after('status');
        });
        
        // إضافة تفضيلات الإشعارات للأدمن
        Schema::table('admins', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable()->after('status');
        });
        
        // إضافة تفضيلات الإشعارات للمساعدين
        Schema::table('assistants', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable()->after('status');
        });
        
        // إضافة تفضيلات الإشعارات للسوبرادمن
        Schema::table('super_admins', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn('notification_preferences');
        });
        
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('notification_preferences');
        });
        
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropColumn('notification_preferences');
        });
        
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropColumn('notification_preferences');
        });
    }
};

