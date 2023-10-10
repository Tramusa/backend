<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chek_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('trip');
            $table->string('programming_doc');
            $table->string('vale_doc');
            $table->string('letter_doc');
            $table->string('stamp_doc');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chek_docs');
    }
};
