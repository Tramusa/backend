<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs_mtto_general', function (Blueprint $table) {

            $table->id();

            $table->string('activity');

            $table->string('area');

            $table->string('building');

            $table->enum('category', [
                'INFRAESTRUCTURA',
                'COMPUTO'
            ]);

            $table->boolean('status')->default(true);

            $table->text('observations')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs_mtto_general');
    }
};