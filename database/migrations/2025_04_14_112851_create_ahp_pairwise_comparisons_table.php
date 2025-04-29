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
        Schema::create('ahp_pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria1_id')->constrained('criterias');
            $table->foreignId('criteria2_id')->constrained('criterias');
            $table->decimal('value', 10, 4); // Presisi 4 digit desimal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_pairwise_comparisons');
    }
};
