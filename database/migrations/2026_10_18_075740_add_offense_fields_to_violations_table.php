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
    Schema::table('violations', function (Blueprint $table) {
        $table->string('offense_code', 10)->nullable()->after('violation_type');
        $table->string('offense_category', 10)->nullable()->after('offense_code');
        $table->unsignedTinyInteger('offense_number')->nullable()->after('offense_category');
        $table->string('sanction_code', 2)->nullable()->after('offense_number');
        $table->string('sanction_label')->nullable()->after('sanction_code');
        $table->boolean('beyond_path')->default(false)->after('sanction_label');
    });
}

public function down(): void
{
    Schema::table('violations', function (Blueprint $table) {
        $table->dropColumn(['offense_code','offense_category','offense_number','sanction_code','sanction_label','beyond_path']);
    });
}
};
