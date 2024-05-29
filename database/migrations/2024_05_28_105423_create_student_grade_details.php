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
        Schema::create('student_grade_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_grade_id');
            $table->unsignedTinyInteger('type');
            $table->decimal('marks', 6, 2);
            $table->string('document_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grade_details');
    }
};
