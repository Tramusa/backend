<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_investigations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('security_flash_alert_id')
                ->nullable()
                ->constrained('security_flash_alerts')
                ->nullOnDelete();

            $table->string('folio', 50)->nullable()->unique();

            $table->string('nombre_incidente', 255)->nullable();
            $table->date('fecha_investigacion')->nullable();
            $table->string('lugar', 500)->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();

            $table->string('departamento', 150)->nullable();
            $table->string('empresa_contratista', 255)->nullable();
            $table->string('clasificacion_actual', 100)->nullable();

            $table->string('unidad')->nullable();
            $table->string('operador', 255)->nullable();
            $table->string('afectado', 255)->nullable();
            $table->string('puesto_afectado', 255)->nullable();
            $table->string('supervisor_monitor', 255)->nullable();

            $table->boolean('investigacion')->default(false);

            $table->longText('descripcion')->nullable();

            $table->longText('arbol_causas')->nullable();
            $table->longText('naturaleza_incidente')->nullable();
            $table->longText('causa_raiz')->nullable();
            $table->longText('como_se_pudo_evitar')->nullable();
            $table->longText('hallazgos_iniciales')->nullable();

            $table->longText('reporte_camara_webfleet')->nullable();
            $table->longText('reporte_readi_score')->nullable();
            $table->longText('reporte_webfleet')->nullable();
            $table->longText('otra_informacion')->nullable();

            $table->longText('primeros_auxilios')->nullable();
            $table->longText('discapacidad')->nullable();
            $table->longText('danio_evaluado')->nullable();
            $table->string('especialista', 255)->nullable();

            $table->enum('status', ['draft', 'finalized'])
                ->default('draft');

            $table->timestamp('finalized_at')->nullable();

            $table->foreignId('finalized_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('fecha');
            $table->index('clasificacion_actual');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_investigations');
    }
};