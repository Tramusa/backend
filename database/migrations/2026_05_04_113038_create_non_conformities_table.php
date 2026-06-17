<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonConformitiesTable extends Migration
{
    public function up()
    {
        Schema::create('non_conformities', function (Blueprint $table) {

            $table->id();
            /* ================= REGISTRO ================= */
            $table->string('number')->unique();
            $table->date('date');
            $table->date('date_commitment')
                ->nullable();
            $table->text('problem');
            $table->string('detected');
            $table->string('affects');
            $table->foreignId('responsible')
                ->constrained('users');
            $table->string('area');
            /*      TYPE        */
            $table->enum('type', [
                'non_conformity',
                'opportunity_improvement'
            ])->nullable();
            /*     STATUS FLOW       */
            $table->enum('status', [
                'registered',
                'evaluation_pending',
                'analysis_pending',
                'relation',
                'pareto',
                'action_plan_pending',
                'finished'
            ])->default('registered');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('non_conformities');
    }
}