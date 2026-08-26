<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entry_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_entry')
                ->constrained('inventory_entries')
                ->cascadeOnDelete();

            // Referencia al producto del catálogo
            $table->foreignId('id_product')
                ->constrained('products_services')
                ->restrictOnDelete();

            // Copia de la información del producto
            // en el momento de registrar la entrada
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit_measure')->nullable();
            $table->text('description')->nullable();

            // Datos específicos de esta compra
            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 14, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entry_details');
    }
};