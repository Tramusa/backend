<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_entry')->constrained('inventory_entries');
            $table->foreignId('id_product')->constrained('products_services');
            $table->integer('quality');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entry_details');
    }
};
