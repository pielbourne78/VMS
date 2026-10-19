<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('offenses', function (Blueprint $table) {
        $table->id();
        $table->string('code', 10)->unique();
        $table->string('category', 10);          // minor | major
        $table->string('title');
        $table->json('sanction_path')->nullable(); // major only, e.g. ["B","C","D","E"]
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    foreach (config('offenses.offenses') as $code => $o) {
        DB::table('offenses')->insert([
            'code' => $code,
            'category' => $o[0],
            'title' => $o[1],
            'sanction_path' => isset($o[2]) ? json_encode($o[2]) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

public function down(): void
{
    Schema::dropIfExists('offenses');
}
};
