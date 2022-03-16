<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->default('default.png');
            $table->string('email')->unique();
            $table->string('password');
        });
        Schema::create('userFavorites', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_barber');
        });
        Schema::create('userAppointments', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_barber');
            $table->datetime('ap_datetime');
        });
        Schema::create('barbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->default('default.png');
            $table->float('stars')->default('0');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
        Schema::create('barberPhotos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barber');
            $table->string('url');
        });
        Schema::create('barberReviews', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barber');
            $table->float('rate');
        });
        Schema::create('barberServices', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barber');
            $table->string('name');
            $table->float('price');
        });
        Schema::create('barberTestimonials', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barber');
            $table->string('name');
            $table->float('rate');
            $table->string('body');
        });
        Schema::create('barberAvailability', function (Blueprint $table) {
            $table->id();
            $table->integer('id_barber');
            $table->integer('weekday');
            $table->text('hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('userFavorites');
        Schema::dropIfExists('userAppointments');
        Schema::dropIfExists('barbers');
        Schema::dropIfExists('barberPhotos');
        Schema::dropIfExists('barberReviews');
        Schema::dropIfExists('barberService');
        Schema::dropIfExists('barberTestimonials');
        Schema::dropIfExists('barberAvailability');
    }
}
