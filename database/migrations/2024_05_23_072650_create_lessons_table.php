<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->date('date');
//            $table->foreignId('topic_id')->constrained();
            $table->string('topic');
            $table->foreignId('teacher_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('lesson_type_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
