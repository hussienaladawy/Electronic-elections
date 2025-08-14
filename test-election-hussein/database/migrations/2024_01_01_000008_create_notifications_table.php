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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['administrative', 'educational', 'security', 'reminder', 'announcement', 'alert'])
                  ->default('announcement');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal');
            $table->json('target_audience'); // المستهدفون
            $table->json('channels'); // قنوات الإرسال
            $table->timestamp('scheduled_at')->nullable(); // موعد الإرسال المجدول
            $table->timestamp('sent_at')->nullable(); // وقت الإرسال الفعلي
            $table->enum('status', ['draft', 'scheduled', 'pending', 'sending', 'sent', 'failed', 'cancelled'])
                  ->default('draft');
            $table->json('metadata')->nullable(); // بيانات إضافية
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            // الفهارس
            $table->index(['status', 'scheduled_at']);
            $table->index(['type', 'priority']);
            $table->index('created_by');
            
            // المفاتيح الخارجية
            $table->foreign('created_by')->references('id')->on('super_admins')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('super_admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

