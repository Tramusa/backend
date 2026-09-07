<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_transfer')
                ->constrained('inventory_transfers')
                ->cascadeOnDelete();

            $table->foreignId('id_product')
                ->constrained('products_services')
                ->cascadeOnDelete();

            $table->decimal('quantity', 12, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfer_details');
    }
};