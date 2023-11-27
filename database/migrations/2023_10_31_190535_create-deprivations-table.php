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
        Schema::create('deprivations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('admin_users')->onDelete('cascade');
            $table->string('file_path');
            $table->date('date_of_deprivation');
            $table->foreignId('lifted_by')->nullable()->constrained('admin_users')->onDelete('set null');  
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
