<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date')->default('2000-01-01');
            $table->date('date_attended')->nullable();        
            $table->integer('status')->default(1);
            $table->date('date_in')->nullable();
            $table->text('repair')->nullable();
            $table->string('requisitions')->nullable();
            $table->float('odometro')->nullable();
            $table->string('spare_parts')->nullable();
            $table->float('total_parts')->nullable();
            $table->intfloateger('total_mano')->nullable();
            $table->integer('authorize')->nullable();
            $table->integer('perform')->nullable();
            $table->integer('operator')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
