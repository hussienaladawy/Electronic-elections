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
        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_recipient_id');
            $table->string('channel'); // قناة التسليم (email, sms, push, in_app)
            $table->string('destination'); // الوجهة (email address, phone number, etc.)
            $table->enum('status', ['pending', 'sending', 'delivered', 'failed', 'bounced'])
                  ->default('pending');
            $table->text('response')->nullable(); // استجابة الخدمة
            $table->json('metadata')->nullable(); // بيانات إضافية
            $table->timestamp('sent_at')->nullable(); // وقت الإرسال
            $table->timestamp('delivered_at')->nullable(); // وقت التسليم
            $table->integer('retry_count')->default(0); // عدد المحاولات
            $table->timestamp('next_retry_at')->nullable(); // موعد المحاولة التالية
            $table->timestamps();
            
            // الفهارس
            $table->index(['notification_recipient_id', 'channel']);
            $table->index(['status', 'next_retry_at']);
            $table->index('channel');
            
            // المفاتيح الخارجية
            $table->foreign('notification_recipient_id')->references('id')->on('notification_recipients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
    }
};

