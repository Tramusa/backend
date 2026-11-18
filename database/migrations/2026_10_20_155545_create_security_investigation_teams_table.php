<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_investigation_teams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('security_investigation_id')
                ->constrained('security_investigations')
                ->cascadeOnDelete();

            $table->unsignedInteger('orden')->default(0);
            $table->string('puesto', 255)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('firma_ruta', 500)->nullable();

            $table->timestamps();

            $table->index(
                'security_investigation_id',
                'sit_investigation_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_investigation_teams');
    }
};