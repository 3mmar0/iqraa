<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('intro_video_path')->nullable()->after('image_path');
            $table->string('intro_video_disk')->nullable()->after('intro_video_path');
            $table->string('intro_video_original_name')->nullable()->after('intro_video_disk');
            $table->string('intro_video_mime')->nullable()->after('intro_video_original_name');
            $table->unsignedBigInteger('intro_video_size')->nullable()->after('intro_video_mime');
        });

        Schema::create('upload_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->default('course_intro_video');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('total_size');
            $table->unsignedInteger('chunk_size');
            $table->unsignedInteger('total_chunks');
            $table->json('received_chunks')->nullable();
            $table->string('status')->default('pending');
            $table->string('temp_key');
            $table->string('client_fingerprint')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_sessions');

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'intro_video_path',
                'intro_video_disk',
                'intro_video_original_name',
                'intro_video_mime',
                'intro_video_size',
            ]);
        });
    }
};
