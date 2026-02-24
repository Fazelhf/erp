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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_no')->unique(); // شماره نامه (مثلاً: ۱۴۰۲/۱۰/۰۵)
            $table->string('title'); // موضوع نامه
            $table->text('content'); // متن اصلی نامه
            
            // فرستنده اصلی (کاربری که نامه را ثبت کرده)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); 
            
            $table->enum('type', ['internal', 'incoming', 'outgoing']); // نوع: داخلی، وارده، صادره
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // وضعیت کلی
            
            $table->string('attachment')->nullable(); // مسیر فایل پیوست (مثلاً در پوشه storage)
            $table->timestamps(); // ایجاد دو ستون created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};