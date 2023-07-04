<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units_p_d_fs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('unit_id');
            $table->integer('type_unit');
            $table->string('location');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units_p_d_fs');
    }
};
