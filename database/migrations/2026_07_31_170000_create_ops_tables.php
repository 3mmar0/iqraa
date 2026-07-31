<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('ip')->nullable();
            $table->json('properties')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index(['target_type', 'target_id']);
            $table->index('actor_id');
        });

        Schema::create('report_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->string('format')->default('csv');
            $table->string('status')->default('queued');
            $table->string('file_path')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['requester_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_jobs');
        Schema::dropIfExists('audit_logs');
    }
};