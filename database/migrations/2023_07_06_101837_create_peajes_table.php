<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peajes', function (Blueprint $table) {
            $table->id();
            $table->string('caseta');
            $table->string('name');
            $table->string('address');
            $table->string('import2');
            $table->string('import3');
            $table->string('import4');
            $table->string('import5');
            $table->string('import6');
            $table->string('import9');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('peajes');
    }
};
