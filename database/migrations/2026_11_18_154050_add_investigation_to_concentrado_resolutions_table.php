<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Agregar INVESTIGACION como nuevo origen
        DB::statement("
            ALTER TABLE concentrado_resolutions
            MODIFY source ENUM('ACR', 'INVESTIGACION', 'MANUAL')
            NOT NULL DEFAULT 'MANUAL'
        ");

        Schema::table('concentrado_resolutions', function (Blueprint $table) {
            $table->foreignId('security_investigation_id')
                ->nullable()
                ->after('non_conformity_id')
                ->constrained('security_investigations')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('concentrado_resolutions', function (Blueprint $table) {
            $table->dropForeign(['security_investigation_id']);
            $table->dropColumn('security_investigation_id');
        });

        DB::statement("
            ALTER TABLE concentrado_resolutions
            MODIFY source ENUM('ACR', 'MANUAL')
            NOT NULL DEFAULT 'MANUAL'
        ");
    }
};
