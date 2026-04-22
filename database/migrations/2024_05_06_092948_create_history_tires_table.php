<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('history_tires', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tire_ctrl')
                ->constrained('tires_control')
                ->onDelete('cascade')
                ->nullable();

            $table->string('activity');
            $table->dateTime('date');
            $table->text('details')->nullable();

            // 🔥 usuario que hizo el cambio
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('history_tires');
    }
};
