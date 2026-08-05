<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs_mtto_general_schedule', function (Blueprint $table) {

            $table->id();

            $table->foreignId('program_id')
                ->constrained('programs_mtto_general')
                ->cascadeOnDelete();

            // Año del cronograma
            $table->integer('year');

            // Semana ISO (1-53)
            $table->integer('week');

            // Fecha programada
            $table->date('scheduled_date')->nullable();

            // Estado de la programación
            $table->enum('status', [
                'pending',
                'generated',
                'completed',
                'cancelled'
            ])->default('pending');

            // Fecha en que realmente se realizó
            $table->date('completed_date')->nullable();

            $table->timestamps();

            $table->unique(['program_id', 'year', 'week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs_mtto_general_schedule');
    }
};