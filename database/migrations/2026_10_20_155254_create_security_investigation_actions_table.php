<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_investigation_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('security_investigation_id')
                ->constrained('security_investigations')
                ->cascadeOnDelete();

            $table->enum('tipo', [
                'inmediata',
                'repeticion'
            ]);

            $table->unsignedInteger('numero')->default(1);

            $table->longText('descripcion')->nullable();

            // Responsable seleccionado de los usuarios del sistema
            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('fecha_compromiso')->nullable();

            $table->timestamps();

            $table->index(
                ['security_investigation_id', 'tipo'],
                'sia_investigation_tipo_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_investigation_actions');
    }
};
