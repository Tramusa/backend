<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_bancks', function (Blueprint $table) {
            $table->id();
            $table->integer('id_supplier');
            $table->string('banck');
            $table->string('account')->nullable();
            $table->string('clabe')->nullable();
            $table->string('moneda');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('supplier_bancks');
    }
};
