<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_annexes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            // 0 = borrador / en proceso
            // 1 = finalizado
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
            $table->unique('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_annexes');
    }
};