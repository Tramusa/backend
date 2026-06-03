<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectiveActionsTable extends Migration
{
    public function up()
    {
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('action_plan_cause_id')
                ->constrained('action_plan_causes')
                ->onDelete('cascade');

            $table->text('corrective_action');

            $table->date('commitment_date')->nullable();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('corrective_actions');
    }
}