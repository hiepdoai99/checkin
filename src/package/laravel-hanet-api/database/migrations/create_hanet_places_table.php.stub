<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHanetPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanet_places', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('placeID')->unique();
            $table->string('address');
            $table->string('name');
            $table->string('userID');
            $table->unsignedBigInteger('dept_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hanet_places');
    }
}
