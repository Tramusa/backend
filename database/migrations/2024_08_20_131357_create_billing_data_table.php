<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billing_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')
                ->constrained('purchase_orders')
                ->onDelete('cascade') // Eliminación en cascada al borrar una orden e pago
                ->nullable();
            $table->string('date');
            $table->string('payment_form');
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_data');
    }
};
