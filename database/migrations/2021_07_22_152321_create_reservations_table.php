<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id')->unique()->unsigned()->nullable(false);

            $table->tinyInteger('boat_id')->nullable(false)->unsigned();
            $table->foreign('boat_id')->references('id')->on('boats')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->nullable(false)->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->tinyInteger('number_of_passengers')->nullable(false)->unsigned();

            $table->dateTime('start_of_rent')->nullable(false);

            $table->dateTime('end_of_rent')->nullable(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
