<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('university');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->foreignId('academic_year_id')->nullable()->after('university')->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->after('academic_year_id')->constrained()->nullOnDelete();
            $table->foreignId('group_id')->nullable()->after('semester_id')->constrained()->nullOnDelete();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('instructor_user_id')->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->after('academic_year_id')->constrained()->nullOnDelete();
            $table->decimal('price', 12, 2)->default(0)->after('hours');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('status');
            $table->timestamp('published_at')->nullable()->after('is_locked');
            $table->foreignId('quiz_id')->nullable()->after('published_at')->constrained('quizzes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('quiz_id');
            $table->dropColumn(['is_locked', 'published_at']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('academic_year_id');
            $table->dropConstrainedForeignId('semester_id');
            $table->dropColumn('price');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('academic_year_id');
            $table->dropConstrainedForeignId('semester_id');
            $table->dropConstrainedForeignId('group_id');
            $table->dropColumn(['gender', 'last_login_at']);
        });
    }
};
