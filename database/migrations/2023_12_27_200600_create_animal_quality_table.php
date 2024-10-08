<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('animal_quality', function (Blueprint $table) {
            $table->id();
            $table->boolean('value')->nullable();
            $table->foreignId('animal_id')->constrained('animals');
            $table->foreignId('quality_id')->constrained('qualities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('animal_quality');
    }
};
