<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
     public function up()
    {
        Schema::create('retrabajos', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('type'); // tipo unidad
            $table->unsignedBigInteger('unit');  // id unidad
            $table->unsignedTinyInteger('mes');  // 1-12
            $table->unsignedSmallInteger('year');

            $table->integer('cantidad')->default(0);

            $table->timestamps();

            $table->unique(['type', 'unit', 'mes', 'year']);
        });
    }

    

    public function down()
    {
        Schema::dropIfExists('retrabajos');
    }
};
