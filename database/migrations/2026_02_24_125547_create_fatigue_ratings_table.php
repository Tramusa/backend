<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fatigue_ratings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('performed_by'); // quien realizó la evaluación

            $table->tinyInteger('question_1');
            $table->tinyInteger('question_2');
            $table->tinyInteger('question_3');
            $table->tinyInteger('question_4');
            $table->tinyInteger('question_5');
            $table->tinyInteger('question_6');
            $table->tinyInteger('question_7');

            $table->integer('total_points');
            $table->string('level'); // bajo, medio, alto

            $table->timestamps();

            $table->foreign('operator_id')->references('id')->on('users');
            $table->foreign('performed_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fatigue_ratings');
    }
};