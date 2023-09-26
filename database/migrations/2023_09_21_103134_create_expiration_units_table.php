<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expiration_units', function (Blueprint $table) {
            $table->id();            
            $table->string('type_unit');
            $table->integer('unit');
            $table->string('description');
            $table->date('date_expiration')->default('2000-01-01');
            $table->date('date_attended')->nullable();        
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expiration_units');
    }
};
