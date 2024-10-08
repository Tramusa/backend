<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_flash_securities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->text('question1');
            $table->text('question2');
            $table->text('question3');
            $table->text('question4');
            $table->text('question5');
            $table->text('question6');
            $table->string('status')->default('Pendiente');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('test_flash_securities');
    }
};
