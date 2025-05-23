<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_inventory')->constrained('warehouses');
            $table->foreignId('id_product')->constrained('products_services');
            $table->integer('quality');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_details');
    }
};
