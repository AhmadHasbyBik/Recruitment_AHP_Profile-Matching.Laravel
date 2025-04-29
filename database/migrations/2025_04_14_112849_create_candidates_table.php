<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->foreignId('vacancy_id')->constrained();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Tambahkan user_id
            $table->text('resume')->nullable();
            $table->enum('status', ['registered', 'interviewed', 'accepted', 'rejected'])->default('registered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};