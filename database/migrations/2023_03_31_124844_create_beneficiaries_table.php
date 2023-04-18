<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name_beneficiary');
            $table->string('a_paterno_beneficiary');
            $table->string('a_materno_beneficiary');
            $table->string('relationship');
            $table->string('cell_beneficiary');
            $table->string('percentage');
            $table->string('birthdate_beneficiary');
            $table->string('street_beneficiary');
            $table->string('suburb_beneficiary');
            $table->string('municipality_beneficiary');
            $table->string('state_beneficiary');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
};
