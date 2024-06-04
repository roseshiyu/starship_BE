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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', '50');
            $table->string('description', '1000');
            $table->string('code', '50')->unique();
            $table->decimal('fee', '8', '2');
            $table->unsignedTinyInteger('weeks');
            $table->unsignedTinyInteger('credits');
            $table->text('category_ids')->nullable();
            $table->tinyText('prerequisites'); // subjects_id, cour...
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_subjects');
    }
};
