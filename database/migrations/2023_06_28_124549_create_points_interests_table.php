<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('points_interests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street');
            $table->string('suburb');
            $table->string('city');
            $table->string('state');
            $table->string('cp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('points_interests');
    }
};
