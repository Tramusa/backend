<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tires', function (Blueprint $table) {
            $table->id();
            $table->string('serie');
            $table->string('brand');
            $table->string('extent');
            $table->string('model');
            $table->string('layers');
            $table->string('number_dot');
            $table->string('lrh_lrg');
            $table->string('simple_maximum');
            $table->string('double_maximum');
            $table->string('suitable_renewal');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tires');
    }
};
