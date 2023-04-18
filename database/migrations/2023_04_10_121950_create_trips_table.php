<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('hour')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->longText('detaills')->nullable();
            $table->integer('operator')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
