<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('instructor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('image_path')->nullable();
            $table->decimal('hours', 8, 2)->default(0);
            $table->string('status')->default('draft');
            $table->string('schedule_text')->nullable();
            $table->string('term_label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('course_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_note')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'course_id', 'status']);
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active');
            $table->string('source')->default('access_request');
            $table->foreignId('access_request_id')->nullable()->constrained('course_access_requests')->nullOnDelete();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('disk')->default('local_private');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('not_started');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('last_position_seconds')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });

        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('lesson_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_comments');
        Schema::dropIfExists('lesson_notes');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('course_access_requests');
        Schema::dropIfExists('courses');
    }
};
