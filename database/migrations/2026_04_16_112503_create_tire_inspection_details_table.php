<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tire_inspection_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inspection_id');
            $table->unsignedBigInteger('tire_id');

            $table->decimal('psi', 8, 2)->nullable();

            // INTERNOS
            $table->decimal('internal_1', 8, 2)->nullable();
            $table->decimal('internal_2', 8, 2)->nullable();
            $table->decimal('internal_3', 8, 2)->nullable();

            // CENTRO
            $table->decimal('center_1', 8, 2)->nullable();
            $table->decimal('center_2', 8, 2)->nullable();
            $table->decimal('center_3', 8, 2)->nullable();

            // EXTERNO
            $table->decimal('external_1', 8, 2)->nullable();
            $table->decimal('external_2', 8, 2)->nullable();
            $table->decimal('external_3', 8, 2)->nullable();

            $table->decimal('average', 8, 2)->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('inspection_id')
                ->references('id')
                ->on('tire_inspections')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tire_inspection_details');
    }
};