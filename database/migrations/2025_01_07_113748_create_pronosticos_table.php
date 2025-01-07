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
        Schema::create('pronosticos', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->unsignedBigInteger('ciudad_id'); // ciudad_id INT
            $table->dateTime('fecha_hora'); // fecha_hora DATETIME
            $table->decimal('temperatura', 5, 2); // temperatura DECIMAL(5, 2)
            $table->decimal('temp_min', 5, 2); // temp_min DECIMAL(5, 2)
            $table->decimal('temp_max', 5, 2); // temp_max DECIMAL(5, 2)
            $table->integer('presion'); // presion INT
            $table->integer('humedad'); // humedad INT
            $table->decimal('viento', 5, 2); // viento DECIMAL(5, 2)
            $table->string('descripcion', 255); // descripcion VARCHAR(255)
            $table->string('icono', 255); // icono VARCHAR(255)
            $table->decimal('lluvia', 5, 2)->default(0); // lluvia DECIMAL(5, 2) DEFAULT 0
            $table->timestamps(); // created_at y updated_at

            // Relación con la tabla ciudades
            $table->foreign('ciudad_id')->references('id')->on('ciudades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronosticos');
    }
};
