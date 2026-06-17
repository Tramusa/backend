<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIshikawaCausesTable extends Migration
{
    public function up()
    {
        Schema::create('ishikawa_causes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ishikawa_id')
                ->constrained()
                ->onDelete('cascade');

            $table->enum('category', [
                'maquinaria',
                'personas',
                'metodo',
                'materiales'
            ]);

            $table->text('description');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ishikawa_causes');
    }
}