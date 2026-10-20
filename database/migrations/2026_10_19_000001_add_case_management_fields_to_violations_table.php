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
            $table->foreignId('case_owner_id')->nullable()->constrained('users')->nullOnDelete()->after('issued_by');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete()->after('case_owner_id');
            $table->foreignId('last_updated_by')->nullable()->constrained('users')->nullOnDelete()->after('recorded_by');
            $table->timestamp('escalated_at')->nullable()->after('last_updated_by');
            $table->text('escalation_reason')->nullable()->after('escalated_at');
            $table->string('review_level')->default('normal')->after('escalation_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('case_owner_id');
            $table->dropConstrainedForeignId('recorded_by');
            $table->dropConstrainedForeignId('last_updated_by');
            $table->dropColumn(['escalated_at', 'escalation_reason', 'review_level']);
        });
    }
};
