<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop default Laravel users table and replace with custom one
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('role_id')->constrained('roles', 'role_id')->onDelete('restrict');
            $table->foreignId('employee_id')->nullable()->constrained('employees', 'employee_id')->onDelete('cascade');
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
