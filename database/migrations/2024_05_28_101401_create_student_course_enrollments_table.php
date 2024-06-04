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
        Schema::create('student_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('course_id');
            $table->string('entry_requirement_proof', 2048)->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedInteger('enrolled_admin_id')->nullable(); // create
            $table->unsignedInteger('verified_admin_id')->nullable(); // 2-layer update
            $table->string('remarks', 1024)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_course_enrollments');
    }
};
