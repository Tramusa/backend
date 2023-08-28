<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('autobuses', function (Blueprint $table) {
            $table->id();
            $table->string('no_economic');
            $table->string('brand');
            $table->string('model');
            $table->string('no_seriously');
            $table->string('no_placas');
            $table->date('expiration_placas');
            $table->string('circulation_card');
            $table->date('expiration_circulation');
            $table->integer('ejes');
            $table->integer('no_passengers');
            $table->string('front')->nullable();
            $table->string('rear')->nullable();
            $table->string('left')->nullable();
            $table->string('right')->nullable();
            $table->integer('user');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('autobuses');
    }
};
