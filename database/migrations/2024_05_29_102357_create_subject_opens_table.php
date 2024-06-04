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
        Schema::create('course_subject_opens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_subject_id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('class_id');
            $table->timestamp('class_started_at');
            $table->timestamp('class_ended_at');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_subject_opens');
    }
};
