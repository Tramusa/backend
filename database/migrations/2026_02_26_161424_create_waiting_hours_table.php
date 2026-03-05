<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waiting_hours', function (Blueprint $table) {

            $table->id();

            // Relación unidad
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('type');

            // Orden relacionada
            $table->unsignedBigInteger('order_id');

            // Horas registradas
            $table->decimal('hours', 8, 2)->default(0);

            // Justificación
            $table->text('justification')->nullable();

            // Quien registró
            $table->string('performed_by')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waiting_hours');
    }
};
