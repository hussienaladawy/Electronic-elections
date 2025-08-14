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
        // تحديث جدول الأصوات لإضافة حقول الأمان المتقدمة
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'vote_hash')) {
                $table->string('vote_hash')->unique()->after('candidate_id'); // هاش فريد للصوت
            }
            if (!Schema::hasColumn('votes', 'encrypted_vote')) {
                $table->text('encrypted_vote')->after('vote_hash'); // الصوت المشفر
            }
            if (!Schema::hasColumn('votes', 'verification_code')) {
                $table->string('verification_code', 20)->unique()->after('encrypted_vote'); // رمز التحقق
            }
            if (!Schema::hasColumn('votes', 'security_metadata')) {
                $table->json('security_metadata')->nullable()->after('verification_code'); // بيانات أمنية إضافية
            }
            if (!Schema::hasColumn('votes', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('security_metadata'); // عنوان IP
            }
            if (!Schema::hasColumn('votes', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address'); // معلومات المتصفح
            }
            if (!Schema::hasColumn('votes', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('user_agent'); // حالة التحقق
            }
            if (!Schema::hasColumn('votes', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified'); // وقت التحقق
            }
            
            // فهارس للأداء والأمان
            $table->index(['vote_hash', 'is_verified']);
            $table->index(['verification_code', 'is_verified']);
            $table->index(['election_id', 'voter_id', 'is_verified']);
        });
        
        // تحديث جدول الانتخابات لإضافة إعدادات الأمان
        Schema::table('elections', function (Blueprint $table) {
            $table->json('security_settings')->nullable()->after('description'); // إعدادات الأمان
            $table->boolean('requires_verification')->default(true)->after('security_settings'); // يتطلب تحقق
            $table->integer('max_votes_per_ip')->default(1)->after('requires_verification'); // حد الأصوات لكل IP
            $table->boolean('allow_vote_verification')->default(true)->after('max_votes_per_ip'); // السماح بالتحقق من الصوت
        });
        
        // تحديث جدول المرشحين لإضافة معلومات إضافية
        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'biography')) {
                $table->text('biography')->nullable(); // السيرة الذاتية
            }
            if (!Schema::hasColumn('candidates', 'image_url')) {
                $table->string('image_url')->nullable()->after('biography'); // صورة المرشح
            }
            if (!Schema::hasColumn('candidates', 'social_media')) {
                $table->json('social_media')->nullable()->after('image_url'); // وسائل التواصل الاجتماعي
            }
            if (!Schema::hasColumn('candidates', 'display_order')) {
                $table->integer('display_order')->default(0)->after('social_media'); // ترتيب العرض
            }
            
            $table->index(['election_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['vote_hash', 'is_verified']);
            $table->dropIndex(['verification_code', 'is_verified']);
            $table->dropIndex(['election_id', 'voter_id', 'is_verified']);
            
            $table->dropColumn([
                'vote_hash',
                'encrypted_vote',
                'verification_code',
                'security_metadata',
                'ip_address',
                'user_agent',
                'is_verified',
                'verified_at'
            ]);
        });
        
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn([
                'security_settings',
                'requires_verification',
                'max_votes_per_ip',
                'allow_vote_verification'
            ]);
        });
        
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropIndex(['election_id', 'display_order']);
            
            $table->dropColumn([
                'biography',
                'image_url',
                'social_media',
                'display_order'
            ]);
        });
    }
};

