<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('type_person');
            $table->string('business_name');
            $table->string('tradename');
            $table->string('RFC');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('municipality')->nullable();
            $table->string('location')->nullable();
            $table->string('street')->nullable();           
            $table->integer('no_int')->nullable();
            $table->integer('no_ext')->nullable();
            $table->string('cologne')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('phone');
            $table->string('e_mail');
            $table->integer('discount')->nullable();
            $table->integer('credit_sale')->nullable();
            $table->integer('credit_days')->nullable();
            $table->integer('credit_limit')->nullable();            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
