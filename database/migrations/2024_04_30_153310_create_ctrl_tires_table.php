<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ctrl_tires', function (Blueprint $table) {
            $table->id(); 
            $table->integer('tire');
            $table->integer('type');
            $table->integer('unit');
            $table->string('installation_date')->nullable();
            $table->string('status')->default('N');
            $table->string('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ctrl_tires');
    }
};
