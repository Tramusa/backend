<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('volteos', function (Blueprint $table) {
            $table->id();
            $table->string('no_economic');
            $table->string('brand');
            $table->string('model');
            $table->string('no_seriously');
            $table->string('no_placas');
            $table->date('expiration_placas');
            $table->string('circulation_card');
            $table->date('expiration_circulation');
            $table->string('insurance_policy');
            $table->date('safe_expiration');
            $table->string('policy_receipt');
            $table->date('expiration_receipt');
            $table->string('physical_mechanical');
            $table->date('physical_expiration');
            $table->integer('ejes');
            $table->integer('volume');
            $table->integer('user');
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('volteos');
    }
};
