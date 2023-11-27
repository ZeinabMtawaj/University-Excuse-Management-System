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
        Schema::create('medical_excuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('admin_users')->onDelete('cascade');  // Ensure the table name is 'teaching_staff'. Adjust if it's different.
            $table->foreignId('teacher_id')->constrained('admin_users')->onDelete('cascade');
            $table->string('file_path');
            $table->date('date');
            $table->timestamps();
        });

        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
