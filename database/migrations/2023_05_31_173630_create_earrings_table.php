<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('earrings', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->integer('unit');
            $table->integer('status')->default(1);
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('earrings');
    }
};
