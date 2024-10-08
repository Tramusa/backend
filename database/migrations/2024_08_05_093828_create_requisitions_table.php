<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_work_area');
            $table->integer('id_collaborator');
            $table->string('date');
            $table->integer('id_parent_account');
            $table->integer('id_title_account');
            $table->integer('id_subtitle_account');
            $table->integer('id_mayor_account');
            $table->string('observations')->nullable();
            $table->string('status');
            $table->string('date_authorized')->nullable();
            $table->integer('authorized')->nullable();
            $table->string('date_atended')->nullable();
            $table->string('analyze')->nullable();
            $table->string('comprobante')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
};
