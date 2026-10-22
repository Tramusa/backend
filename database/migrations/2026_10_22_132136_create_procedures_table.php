
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();

            $table->string('departamento', 50)->index();
            $table->string('nombre', 180);
            $table->text('descripcion')->nullable();

            // Ruta del PDF en el disco privado.
            $table->string('archivo_pdf', 255);

            // Usuario que registró el procedimiento.
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(
                ['departamento', 'nombre'],
                'procedures_department_name_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
