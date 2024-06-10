<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('renovations_tires', function (Blueprint $table) {
            $table->id();          
            $table->integer('tire');
            $table->string('supplier')->nullable();
            $table->string('floor_type')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('renovations_tires');
    }
};
