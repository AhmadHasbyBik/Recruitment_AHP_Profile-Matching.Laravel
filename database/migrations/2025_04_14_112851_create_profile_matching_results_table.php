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
        Schema::create('profile_matching_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained();
            $table->decimal('total_gap', 10, 2)->default(0);
            $table->decimal('total_weighted_gap', 10, 2)->default(0);
            $table->decimal('final_score', 8, 2);
            $table->integer('rank')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable()->useCurrent(); // Pastikan ini ada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_matching_results');
    }
};
