<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remolques', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('no_economic');
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('no_seriously');
            $table->string('color');
            $table->string('capacity');
            $table->string('unit');
            $table->string('no_placas');
            $table->date('expiration_placas');
            $table->string('circulation_card');
            $table->date('expiration_circulation');
            $table->string('dry_measure')->nullable();
            $table->string('wet_measure')->nullable();
            $table->string('seal_inviolability')->nullable();
            $table->string('rear_bumper_size')->nullable();
            $table->string('authorized_capacity')->nullable();
            $table->integer('user');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remolques');
    }
};
