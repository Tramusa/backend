<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tires_control', function (Blueprint $table) {
            $table->id();

            $table->string('dot')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            $table->date('installed_at')->nullable();

            $table->string('status')->default('paused'); 
            // in_use | paused | scrapped

            $table->string('position')->nullable();

            $table->string('burn_number')->nullable();

            $table->decimal('tread_depth', 5, 2)->nullable();

            // 🔥 aquí guardas lo que eliges del select
            $table->string('assignment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tires_control');
    }
};