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

            $table->foreignId('id_inventory')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            $table->foreignId('id_product')
                ->constrained('products_services')
                ->restrictOnDelete();

            // Existencia actual del producto en el almacén
            $table->decimal('quality', 12, 3)->default(0);

            $table->timestamps();

            // Un producto solo puede aparecer una vez
            // dentro del mismo almacén
            $table->unique([
                'id_inventory',
                'id_product'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_details');
    }
};