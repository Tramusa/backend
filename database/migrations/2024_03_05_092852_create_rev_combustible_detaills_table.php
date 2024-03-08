<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rev_combustible_detaills', function (Blueprint $table) {
            $table->id();
            $table->integer('revision_id');
            $table->string('name')->nullable();
            $table->string('distancia_tablero')->nullable();
            $table->string('combustible_cargado')->nullable();
            $table->string('factor_correcion')->nullable();
            $table->string('distancia_ecm')->nullable();
            $table->string('combustible_usado')->nullable();
            $table->string('rendimiento_combustible')->nullable();
            $table->string('ecm_real')->nullable();
            $table->string('peso_bruto')->nullable();
            $table->string('tiempo')->nullable();
            $table->string('consumo_ralenti')->nullable();
            $table->string('tiempo_ralenti')->nullable();
            $table->string('consumo_pto')->nullable();
            $table->string('tiempo_pto')->nullable();
            $table->string('columna');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rev_combustible_detaills');
    }
};
