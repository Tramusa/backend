<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('missing_docs', function (Blueprint $table) {
            $table->id();    
            $table->integer('type');
            $table->integer('unit');    
            $table->string('description');
            $table->string('date');
            $table->integer('inspection');
            $table->boolean('status')->default(1);
            $table->string('date_attended')->nullable();
            $table->integer('attended')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('missing_docs');
    }
};
