<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->nullable();
            $table->string('ceco')->nullable();
            $table->string('name')->nullable();
            $table->string('mail')->nullable();
            $table->string('application_medium')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();            
            $table->string('origin')->nullable();
            $table->integer('p_intermediate')->nullable();
            $table->integer('p_authorized')->nullable();
            $table->string('destination')->nullable();
            $table->date('date')->nullable();
            $table->string('hour')->nullable();
            $table->integer('operator')->nullable();
            $table->string('type')->nullable();
            $table->string('product')->nullable();
            $table->longText('detaills')->nullable();
            $table->boolean('status')->default(0);
            $table->string('reason')->nullable();
            $table->date('end_date')->nullable();
            $table->string('end_hour')->nullable();
            $table->integer('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
