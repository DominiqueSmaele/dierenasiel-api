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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sex');
            $table->dateTime('birth_date')->nullable();
            $table->string('race')->nullable();
            $table->text('description');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('shelter_id')->constrained('shelters');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('animals');
    }
};
