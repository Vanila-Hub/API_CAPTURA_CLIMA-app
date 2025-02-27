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
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('nombre', 255); // nombre VARCHAR(255) NULL
            $table->decimal('latitud', 10, 6)->nullable(); // latitud DECIMAL(10, 6) NULL
            $table->decimal('longitud', 10, 6)->nullable(); // longitud DECIMAL(10, 6) NULL
            $table->timestamps(); // created_at y updated_at
            $table->unique('nombre'); // UNIQUE(nombre)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
