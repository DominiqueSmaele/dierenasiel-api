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
        Schema::create('opening_periods', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->time('open')->nullable();
            $table->time('close')->nullable();
            $table->foreignId('shelter_id')->constrained('shelters');
            $table->timestamps();

            $table->unique(['day', 'shelter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('opening_period');
    }
};
