<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_name', 150);
            $table->string('employee_number', 50)->unique();
            $table->foreignId('department_id')->constrained('departments', 'department_id')->onDelete('restrict');
            $table->foreignId('supervisor_id')->nullable()->constrained('employees', 'employee_id')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
