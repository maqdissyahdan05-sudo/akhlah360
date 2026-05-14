<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id('assessment_id');
            $table->foreignId('assignment_id')->constrained('assignments', 'assignment_id')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('assessment_indicators', 'indicator_id')->onDelete('cascade');
            $table->tinyInteger('score')->unsigned(); // Score 1-5
            $table->timestamps();

            $table->unique(['assignment_id', 'indicator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
