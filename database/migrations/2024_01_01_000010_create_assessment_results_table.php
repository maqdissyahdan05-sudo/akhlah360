<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id('result_id');
            $table->foreignId('period_id')->constrained('assessment_periods', 'period_id')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->decimal('self_score', 5, 2)->nullable();
            $table->decimal('peer_score', 5, 2)->nullable();
            $table->decimal('superior_score', 5, 2)->nullable();
            $table->decimal('subordinate_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->decimal('gap_score', 5, 2)->nullable(); // gap between self and others
            $table->timestamps();

            $table->unique(['period_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
