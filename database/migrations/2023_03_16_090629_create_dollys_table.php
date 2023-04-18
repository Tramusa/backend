<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dollys', function (Blueprint $table) {
            $table->id();
            $table->string('no_seriously');
            $table->string('brand');
            $table->string('model');
            $table->integer('user');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dollys');
    }
};
