<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_flash_alerts', function (Blueprint $table) {
            $table->id();

            // Folio
            $table->string('folio')->unique();

            // Estado
            $table->enum('status', [
                'draft',
                'finalized'
            ])->default('draft');

            // Datos del evento
            $table->string('area_lugar')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->string('departamento')->nullable();

            // Clasificación
            $table->string('clasificacion_actual')->nullable();

            // Unidad / operación
            $table->string('unidad')->nullable();
            $table->string('operador')->nullable();

            // Persona afectada
            $table->string('afectado')->nullable();
            $table->string('puesto_afectado')->nullable();
            $table->string('supervisor_monitor')->nullable();

            // Descripción
            $table->longText('descripcion')->nullable();

            // Evidencia posteriormente
            // Las fotografías NO se guardan aquí.

            // Anexos / acciones
            $table->longText('anexos')->nullable();
            $table->longText('acciones_repeticion')->nullable();
            $table->longText('investigacion')->nullable();

            // Reporta
            $table->string('reportado_por')->nullable();
          
            // Usuario que creó/finalizó
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('finalized_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_flash_alerts');
    }
};