<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units__trips', function (Blueprint $table) {
            $table->id();
            $table->integer('trip');
            $table->integer('type_unit');
            $table->integer('unit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units__trips');
    }
};
