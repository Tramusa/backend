<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    
    public function up()
    {
        Schema::create('tractocamiones', function (Blueprint $table) {
            $table->id();
            $table->string('no_economic');
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('no_seriously');
            $table->string('no_motor');
            $table->string('color');
            $table->string('no_placas');
            $table->date('expiration_placas');
            $table->string('circulation_card');
            $table->date('expiration_circulation');
            $table->string('engine_capacity');
            $table->string('speeds');
            $table->string('differential_pitch');
            $table->string('extent_tire');
            $table->string('tire');
            $table->string('transmission');
            $table->string('ecm');
            $table->string('esn');
            $table->string('cpl');
            $table->integer('ejes');
            $table->integer('user');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tractocamiones');
    }
};
