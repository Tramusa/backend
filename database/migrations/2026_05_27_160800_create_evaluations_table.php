<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('non_conformity_id')
                ->constrained()
                ->onDelete('cascade');
            /*  AC | AP  */
            $table->enum('evaluation_type', [ 'AC','AP' ]);
            /*  VALUES  */
            $table->integer('severity');
            $table->integer('detectability');
            $table->integer('occurrence');
            /*  NPR */
            $table->integer('npr');
            /*  RESULT  */
            $table->enum('result', [
                'correction',
                'corrective_action',
                'cause_analysis'
            ]);
            /*  REQUIRES ANALYSIS */
            $table->boolean('requires_analysis')
                ->default(false);
            /*  USER  */
            $table->foreignId('evaluated_by')
                ->nullable()
                ->constrained('users');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}