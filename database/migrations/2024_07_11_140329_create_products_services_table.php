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
            $table->integer('iva');
            $table->string('unit_measure');
            $table->string('inventory');
            $table->integer('stock')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->timestamps();
        });
    }
  
    public function down()
    {
        Schema::dropIfExists('products_services');
    }
};
