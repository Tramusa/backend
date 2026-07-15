<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisegacs', function (Blueprint $table) {

            $table->id();

            /*
            ===================================================
            ACCION O ACTIVIDAD
            ===================================================
            */

            $table->foreignId('corrective_action_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('activity_id')
                ->nullable()
                ->constrained('action_plan_activities')
                ->cascadeOnDelete();

            /*
            ===================================================
            TIPO
            R
            AM
            C
            CO
            X
            A
            ===================================================
            */

            $table->string('type',5)
                ->nullable();

            /*
            ===================================================
            MESES
            N
            I
            C
            X
            ===================================================
            */

            $table->string('jan',2)->nullable();
            $table->string('feb',2)->nullable();
            $table->string('mar',2)->nullable();
            $table->string('apr',2)->nullable();
            $table->string('may',2)->nullable();
            $table->string('jun',2)->nullable();
            $table->string('jul',2)->nullable();
            $table->string('aug',2)->nullable();
            $table->string('sep',2)->nullable();
            $table->string('oct',2)->nullable();
            $table->string('nov',2)->nullable();
            $table->string('dec',2)->nullable();

            /*
            ===================================================
            AVANCE
            ===================================================
            */

            $table->unsignedTinyInteger('progress')
                ->default(0);

            /*
            ===================================================
            FECHAS
            ===================================================
            */

            $table->date('close_date')
                ->nullable();

            $table->date('next_verification')
                ->nullable();

            /*
            ===================================================
            RECURRENTE
            ===================================================
            */

            $table->boolean('recurrent')
                ->default(false);

            /*
            ===================================================
            OBSERVACIONES
            ===================================================
            */

            $table->text('observations')
                ->nullable();

            $table->timestamps();

            /*
            ===================================================
            INDICES
            ===================================================
            */

            $table->unique(
                ['corrective_action_id']
            );

            $table->unique(
                ['activity_id']
            );

        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'sisegacs'
        );
    }
};