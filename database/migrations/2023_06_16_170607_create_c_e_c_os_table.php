<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCECOsTable extends Migration
{
    public function up()
    {
        Schema::create('c_e_c_os', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade') // Eliminación en cascada al borrar un cliente
                ->nullable();
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('c_e_c_os');
    }
}
