<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akhlaq_values', function (Blueprint $table) {
            $table->id('value_id');
            $table->string('value_name', 100); // Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akhlaq_values');
    }
};
