<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_annex_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_annex_id');
            // Nombre del archivo temporal
            $table->string('image');
            // Para conservar el orden de las fotografías
            $table->unsignedTinyInteger('orden')->default(1);
            $table->timestamps();
            $table->foreign('order_annex_id')
                ->references('id')
                ->on('order_annexes')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_annex_images');
    }
};