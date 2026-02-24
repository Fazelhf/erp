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
        Schema::create('hr_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // درخواست دهنده
    $table->enum('type', ['leave', 'mission']); // مرخصی یا ماموریت
    $table->date('start_date');
    $table->date('end_date');
    $table->text('reason'); // دلیل درخواست
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->foreignId('manager_id')->nullable()->constrained('users'); // مدیری که باید تایید کند
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_requests');
    }
};
