<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mayor_accounts', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); // Definir id como bigInteger y primary key
            $table->string('name');
            $table->string('account')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mayor_accounts');
    }
};
