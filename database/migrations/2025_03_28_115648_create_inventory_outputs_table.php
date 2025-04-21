<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_inventory')->constrained('warehouses');
            $table->date('date');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_outputs');
    }
};
