<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repair_tires', function (Blueprint $table) {
            $table->id();
            $table->integer('tire');
            $table->string('odometro')->nullable();
            $table->string('damage_type')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('repair_tires');
    }
};
