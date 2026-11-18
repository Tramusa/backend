<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('security_investigations', function (Blueprint $table) {
            $table->string('nombre_aplico_primeros_auxilios')->nullable();

            $table->json('agentes')->nullable();
            $table->json('tipos_contacto')->nullable();
            $table->json('actos_inseguros')->nullable();
            $table->json('error_humano')->nullable();
            $table->json('protecciones_factores')->nullable();
            $table->json('condiciones_directas')->nullable();
            $table->json('factor_organizacional')->nullable();
            $table->json('tipo_lesion')->nullable();
            $table->json('parte_cuerpo_afectada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('security_investigations', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_aplico_primeros_auxilios',
                'agentes',
                'tipos_contacto',
                'actos_inseguros',
                'error_humano',
                'protecciones_factores',
                'condiciones_directas',
                'factor_organizacional',
                'tipo_lesion',
                'parte_cuerpo_afectada',
            ]);
        });
    }
};
