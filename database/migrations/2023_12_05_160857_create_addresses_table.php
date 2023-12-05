<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up() : void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->point('coordinates', 4326);
            $table->string('street');
            $table->string('number');
            $table->string('box_number')->nullable();
            $table->string('zipcode');
            $table->string('city');
            $table->foreignId('country_id')->constrained('countries');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('addresses');
    }
};
