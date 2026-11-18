<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_investigation_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('security_investigation_id')
                ->constrained('security_investigations')
                ->cascadeOnDelete();

            $table->string('tipo', 50);
            $table->string('ruta', 500);
            $table->string('nombre_original')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano')->nullable();
            $table->unsignedInteger('orden')->default(0);

            $table->timestamps();

            $table->index(
                ['security_investigation_id', 'tipo'],
                'sii_investigation_tipo_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_investigation_images');
    }
};