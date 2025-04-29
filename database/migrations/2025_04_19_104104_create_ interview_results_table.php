<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interview_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('notes')->nullable();
            $table->enum('decision', ['accepted', 'rejected', 'hold'])->default('hold');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_results');
    }
};