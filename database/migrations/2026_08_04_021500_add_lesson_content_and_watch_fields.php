<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->longText('content_html')->nullable()->after('description');
            $table->foreignId('main_media_asset_id')
                ->nullable()
                ->after('quiz_id')
                ->constrained('media_assets')
                ->nullOnDelete();
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->timestamp('video_completed_at')->nullable()->after('last_position_seconds');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('main_media_asset_id');
            $table->dropColumn('content_html');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropColumn('video_completed_at');
        });
    }
};
