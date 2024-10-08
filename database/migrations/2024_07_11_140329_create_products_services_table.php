<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products_services', function (Blueprint $table) {
            $table->id();
            $table->integer('category');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->float('isr')->nullable();            
            $table->float('iva');
            $table->float('ret_iva')->nullable();
            $table->integer('ret_ish')->nullable();
            $table->string('unit_measure');
            $table->string('inventory');
            $table->float('stock')->nullable();
            $table->float('min')->nullable();
            $table->float('max')->nullable();
            $table->timestamps();
        });    
    }
  
    public function down()
    {
        Schema::dropIfExists('products_services');
    }
};
