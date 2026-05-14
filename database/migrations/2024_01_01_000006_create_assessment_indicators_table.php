<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_indicators', function (Blueprint $table) {
            $table->id('indicator_id');
            $table->foreignId('value_id')->constrained('akhlaq_values', 'value_id')->onDelete('cascade');
            $table->text('indicator_statement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_indicators');
    }
};
