<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('security_flash_alert_images', function (Blueprint $table) {

            $table->id();

            $table->foreignId('security_flash_alert_id')
                ->constrained('security_flash_alerts')
                ->cascadeOnDelete();

            $table->enum('tipo', [
                'evidencia',
                'anexo',
                'webfleet',
            ]);

            $table->string('imagen');
            $table->unsignedTinyInteger('orden')->default(1);
            $table->timestamps();

            // Nombre corto para evitar límite de MySQL
            $table->index(
                ['security_flash_alert_id', 'tipo', 'orden'],
                'sfai_alert_tipo_orden_idx'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('security_flash_alert_images');
    }
};