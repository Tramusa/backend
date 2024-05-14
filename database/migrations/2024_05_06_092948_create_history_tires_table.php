<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_tires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tire_ctrl')
                ->constrained('ctrl_tires')
                ->onDelete('cascade') // Eliminación en cascada al borrar un tire de ctrl_tires
                ->nullable();
            $table->string('activity');
            $table->string('date');
            $table->string('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_tires');
    }
};
