<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('applies_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'slug']);
            $table->index(['company_id', 'applies_to']);
        });

        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')
                  ->constrained('workflow_definitions')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('approval');
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('assignee_role')->nullable();
            $table->unsignedBigInteger('assignee_user_id')->nullable();
            $table->json('conditions')->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->index(['workflow_definition_id', 'order']);
        });

        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')
                  ->constrained('workflow_definitions')
                  ->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->unsignedBigInteger('initiator_id');
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->foreignId('current_step_id')
                  ->nullable()
                  ->constrained('workflow_steps')
                  ->nullOnDelete();
            $table->string('status')->default('running');
            $table->json('context')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['company_id', 'initiator_id']);
        });

        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')
                  ->constrained('workflow_instances')
                  ->cascadeOnDelete();
            $table->foreignId('from_step_id')
                  ->nullable()
                  ->constrained('workflow_steps')
                  ->nullOnDelete();
            $table->foreignId('to_step_id')
                  ->nullable()
                  ->constrained('workflow_steps')
                  ->nullOnDelete();
            $table->unsignedBigInteger('actor_id');
            $table->string('decision');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['workflow_instance_id']);
            $table->index('actor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflow_definitions');
    }
};
