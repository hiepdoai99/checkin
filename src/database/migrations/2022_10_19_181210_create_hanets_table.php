<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanets', function (Blueprint $table) {
            $table->id();
            $table->text('client_id')->unique();
            $table->text('client_secret');
            $table->text('access_token');
            $table->string('email')->index();
            $table->text('refresh_token')->nullable();
            $table->string('userID')->nullable();
            $table->unsignedBigInteger('expire')->nullable();
            $table->unsignedBigInteger('token_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hanets');
    }
}
