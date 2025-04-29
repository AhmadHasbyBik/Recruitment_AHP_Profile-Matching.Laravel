<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdealProfileValuesTable extends Migration
{
    public function up()
    {
        Schema::create('ideal_profile_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->integer('value')->default(1);
            $table->timestamps();
            
            $table->unique(['vacancy_id', 'criteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ideal_profile_values');
    }
}