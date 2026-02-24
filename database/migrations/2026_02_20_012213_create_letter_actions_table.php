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
       Schema::create('letter_actions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('letter_id')->constrained()->onDelete('cascade');
    $table->foreignId('from_user_id')->constrained('users'); // ارجاع دهنده
    $table->foreignId('to_user_id')->constrained('users');   // ارجاع شونده
    $table->text('description')->nullable(); // هامش یا دستور
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_actions');
    }
};
