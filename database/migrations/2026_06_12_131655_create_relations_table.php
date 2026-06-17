<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('non_conformity_id')
                ->constrained()
                ->cascadeOnDelete();

            // Matriz de relaciones
            $table->json('matrix')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};