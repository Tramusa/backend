<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('revisions_tires', function (Blueprint $table) {
            $table->id();
            $table->integer('tire');
            $table->string('date')->nullable();
            $table->integer('odometro')->nullable();
            $table->integer('psi')->nullable();
            $table->string('internal_1')->nullable();
            $table->string('center_1')->nullable();
            $table->string('external_1')->nullable();
            $table->string('internal_2')->nullable();
            $table->string('center_2')->nullable();
            $table->string('external_2')->nullable();
            $table->string('internal_3')->nullable();
            $table->string('center_3')->nullable();
            $table->string('external_3')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revisions_tires');
    }
};
