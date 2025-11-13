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
        Schema::create('delegaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regiones')->onDelete('cascade');
            $table->string('delegacion', 100);
            $table->string('nivel', 100);
            $table->string('sede', 100);          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegaciones');
    }
};
