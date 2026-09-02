<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'full_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('full_name')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('users', 'student_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('student_id')->nullable()->unique()->after('full_name');
            });
        }

        if (!Schema::hasColumn('users', 'year_level')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('year_level')->nullable()->after('student_id');
            });
        }

        if (!Schema::hasColumn('users', 'section')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('section')->nullable()->after('year_level');
            });
        }

        if (!Schema::hasColumn('users', 'course')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('course')->nullable()->after('section');
            });
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('student')->after('course');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'student_id', 'year_level', 'section', 'course', 'role']);
        });
    }
};
