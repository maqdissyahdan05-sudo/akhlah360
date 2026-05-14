<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->foreignId('period_id')->constrained('assessment_periods', 'period_id')->onDelete('cascade');
            $table->foreignId('rater_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->foreignId('ratee_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->enum('relationship_type', ['self', 'peer', 'superior', 'subordinate']);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['period_id', 'rater_id', 'ratee_id', 'relationship_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
