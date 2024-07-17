<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('title_accounts', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); // Definir id como bigInteger y primary key
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('title_accounts');
    }
};
