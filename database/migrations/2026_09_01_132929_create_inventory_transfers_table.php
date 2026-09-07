<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();

            // Almacén de origen
            $table->foreignId('inventory_origin_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            // Almacén de destino
            $table->foreignId('inventory_destination_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            // Usuario que realiza el traspaso
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Persona que recibe en el almacén destino
            $table->string('receiver');

            $table->dateTime('date');

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transfers');
    }
};