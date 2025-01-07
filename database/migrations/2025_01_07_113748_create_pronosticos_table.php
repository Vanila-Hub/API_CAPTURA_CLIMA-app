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
            $table->integer('fecha_unix'); // fecha_unix INT
            $table->decimal('temperatura', 5, 2)->nullable(); // temperatura DECIMAL(5, 2)
            $table->decimal('temp_min', 5, 2)->nullable(); // temp_min DECIMAL(5, 2)
            $table->decimal('temp_max', 5, 2)->nullable(); // temp_max DECIMAL(5, 2)
            $table->decimal('sensacion_termica', 5, 2)->nullable(); // sensacion_termica DECIMAL(5, 2)
            $table->integer('humedad')->nullable(); // humedad INT
            $table->integer('presion')->nullable(); // presion INT
            $table->decimal('viento', 5, 2)->nullable(); // viento DECIMAL(5, 2)
            $table->string('descripcion', 255)->nullable(); // descripcion VARCHAR(255)
            $table->integer('nubes')->nullable(); // nubes INT
            $table->integer('amanecer')->nullable(); // amanecer INT
            $table->integer('atardecer')->nullable(); // atardecer INT
            $table->decimal('latitud', 10, 6)->nullable(); // latitud DECIMAL(10, 6)
            $table->decimal('longitud', 10, 6)->nullable(); // longitud DECIMAL(10, 6)
            $table->decimal('probabilidad_lluvia', 5, 2)->nullable(); // probabilidad_lluvia DECIMAL(5, 2)
            $table->string('icono', 255)->nullable(); // icono VARCHAR(255)
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
