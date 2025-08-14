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
        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->string('recipient_type'); // نوع المستلم (Voter, Admin, etc.)
            $table->unsignedBigInteger('recipient_id'); // معرف المستلم
            $table->json('preferences')->nullable(); // تفضيلات المستلم
            $table->enum('status', ['pending', 'delivered', 'read', 'clicked', 'failed'])
                  ->default('pending');
            $table->timestamp('read_at')->nullable(); // وقت القراءة
            $table->timestamp('clicked_at')->nullable(); // وقت النقر
            $table->timestamps();
            
            // الفهارس
            $table->index(['notification_id', 'status']);
            $table->index(['recipient_type', 'recipient_id']);
            $table->index('status');
            
            // المفاتيح الخارجية
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_recipients');
    }
};

