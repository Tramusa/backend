<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionPlanActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('action_plan_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('corrective_action_id')
                ->constrained('corrective_actions')
                ->onDelete('cascade');

            $table->text('activity');

            $table->date('commitment_date')->nullable();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('completed')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_plan_activities');
    }
}