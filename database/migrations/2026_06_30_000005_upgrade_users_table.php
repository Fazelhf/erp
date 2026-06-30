<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('companies')
                  ->nullOnDelete();

            $table->string('status')->default('active')->after('password');
            $table->string('avatar')->nullable()->after('status');
            $table->string('locale', 10)->default('fa')->after('avatar');
            $table->string('timezone', 50)->default('Asia/Tehran')->after('locale');
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'status', 'avatar', 'locale', 'timezone', 'deleted_at']);
        });
    }
};
