<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs_mtto_vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->integer('unit');
            $table->string('activity');
            $table->integer('start');
            $table->string('periodicity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs_mtto_vehicles');
    }
};
