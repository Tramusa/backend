<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();            
            $table->integer('supplier');
            $table->string('orders');// List 111, 222, 444
            $table->string('total');
            $table->string('payment');
            $table->string('payment_form');
            $table->string('date');
            $table->string('banck');
            $table->string('reference')->nullable();
            $table->string('comprobante')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_orders');
    }
};
