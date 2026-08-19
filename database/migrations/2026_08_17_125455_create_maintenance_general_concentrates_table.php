<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_general_concentrates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_id')
                ->constrained('programs_mtto_general')
                ->cascadeOnDelete();

            $table->foreignId('schedule_id')
                ->constrained('programs_mtto_general_schedule')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('week');

            $table->boolean('status')->default(false);

            $table->text('observations')->nullable();

            $table->timestamps();

            $table->unique(['program_id', 'schedule_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('maintenance_general_concentrates');
    }
};
