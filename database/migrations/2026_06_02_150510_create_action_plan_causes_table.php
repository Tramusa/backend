<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionPlanCausesTable extends Migration
{
    public function up()
    {
        Schema::create('action_plan_causes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('non_conformity_id')
                ->constrained()
                ->onDelete('cascade');

            /*
             * Nullable porque si el plan viene directo,
             * no existe Ishikawa/Pareto/Relación.
             */
            $table->unsignedBigInteger('ishikawa_cause_id')
                ->nullable();

            $table->text('main_cause')->nullable();

            $table->date('commitment_date')->nullable();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_plan_causes');
    }
}