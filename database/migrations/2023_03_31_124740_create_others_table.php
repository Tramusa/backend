<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('others', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('state_civil');
            $table->string('regimen');
            $table->string('sex');
            $table->string('birthdate');
            $table->string('place_birth');
            $table->string('nationality');
            $table->string('scholarship');
            $table->string('title');
            $table->string('ine');
            $table->string('license')->nullable();
            $table->string('type_license')->nullable();
            $table->string('expiration_license')->nullable();
            $table->string('expiration_psychophysical')->nullable();
            $table->string('expiration_general')->nullable();
            $table->string('rfc');
            $table->string('curp');
            $table->string('socia_health');
            $table->string('umf');
            $table->string('weight');
            $table->string('height');
            $table->string('blood_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('others');
    }
};
