<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('details_requisitions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->foreignId('id_requisition')
                ->constrained('requisitions')
                ->onDelete('cascade') // Eliminación en cascada al borrar una requisision
                ->nullable();
            $table->integer('id_product');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->float('isr')->nullable();            
            $table->float('iva');
            $table->float('ret_iva')->nullable();
            $table->integer('ret_ish')->nullable();
            $table->string('unit_measure');
            $table->integer('cantidad');
            $table->string('justific')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('details_requisitions');
    }
};
