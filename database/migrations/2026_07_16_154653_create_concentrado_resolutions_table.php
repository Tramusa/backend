<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('concentrado_resolutions', function (Blueprint $table) {

        $table->id();

        /*
        |--------------------------------------------------------------------------
        | ORIGEN
        |--------------------------------------------------------------------------
        */
        $table->enum('source', [
            'ACR',
            'MANUAL'
        ])->default('MANUAL');

        /*
        |--------------------------------------------------------------------------
        | REFERENCIAS (solo si viene del ACR)
        |--------------------------------------------------------------------------
        */
        $table->foreignId('non_conformity_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('corrective_action_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('activity_id')
            ->nullable()
            ->constrained('action_plan_activities')
            ->nullOnDelete();

        /*
        |--------------------------------------------------------------------------
        | INFORMACION GENERAL
        |--------------------------------------------------------------------------
        */

        $table->string('folio')->nullable();

        $table->string('area')->nullable();

        $table->longText('resolution');

        $table->string('category')->nullable();

        $table->date('agreement_date')->nullable();

        $table->string('support')->nullable();

        $table->foreignId('responsible_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->date('planned_closure')->nullable();

        $table->date('real_closure')->nullable();

        $table->enum('status',[
            'NO INICIADA',
            'INICIADA',
            'ATRASADA',
            'CERRADA'
        ])->default('NO INICIADA');

        $table->text('observations')
            ->nullable();

        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('concentrado_resolutions');
    }
};
