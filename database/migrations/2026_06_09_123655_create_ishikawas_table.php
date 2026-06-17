<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIshikawasTable extends Migration
{
    public function up()
    {
        Schema::create('ishikawas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('non_conformity_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ishikawas');
    }
}