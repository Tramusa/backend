<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs_mtto_vehicle_schedule', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_mtto_vehicle_id')
                ->constrained('programs_mtto_vehicles')
                ->cascadeOnDelete();

            $table->year('year');
            $table->unsignedTinyInteger('week'); // 1 - 53

            // Estado de la semana
            $table->enum('status', [
                'scheduled',    // Planeado (X)
                'done',         // Realizado en tiempo
                'late',         // Realizado fuera de semana
                'canceled',     // Cancelado
                'rescheduled'   // Reprogramado
            ])->default('scheduled');

            // Ejecución
            $table->date('executed_at')->nullable();
            $table->unsignedTinyInteger('rescheduled_to_week')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->unique(
                ['program_mtto_vehicle_id', 'year', 'week'],
                'pmvs_program_year_week_unique'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs_mtto_vehicle_schedule');
    }
};
