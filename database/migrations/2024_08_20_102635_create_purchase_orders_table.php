<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_requisition')
                ->constrained('requisitions')
                ->onDelete('cascade') // Eliminación en cascada al borrar una requisision
                ->nullable();
            $table->string('date_order');
            $table->integer('id_supplier');
            $table->integer('perform');
            $table->integer('authorize')->nullable();
            $table->string('status')->default('PENDIENTE');
            $table->string('additional')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};
