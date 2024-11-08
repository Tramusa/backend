<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_suppliers', function (Blueprint $table) {
            $table->id();
            $table->float('quality');
            $table->string('billings');
            $table->string('date');
            $table->integer('supplier');
            $table->integer('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_suppliers');
    }
};
