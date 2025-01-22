<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up()
    {
        Schema::create('pronosticos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciudad_id');
            $table->foreign('ciudad_id')->references('id')->on('ciudades')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('fecha_hora');  // Marca de tiempo para fecha y hora
            $table->integer('fecha_unix');  // Marca de tiempo Unix para fecha y hora
            $table->decimal('temperatura', 5, 2);  // Temperatura en Celsius
            $table->decimal('temp_min', 5, 2);  // Temperatura mínima en Celsius
            $table->decimal('temp_max', 5, 2);  // Temperatura máxima en Celsius
            $table->decimal('sensacion_termica', 5, 2);  // Sensación térmica en Celsius
            $table->integer('humedad');  // Porcentaje de humedad
            $table->integer('presion');  // Presión atmosférica en hPa
            $table->integer('viento');  // Velocidad del viento en km/h
            $table->string('descripcion');  // Descripción del clima
            $table->integer('nubes');  // Porcentaje de nubosidad
            $table->integer('amanecer');  // Hora del amanecer como marca de tiempo Unix
            $table->integer('atardecer');  // Hora del atardecer como marca de tiempo Unix
            $table->decimal('probabilidad_lluvia', 5, 2)->default(0);  // Probabilidad de lluvia en porcentaje
            $table->string('icono');  // Identificador del icono del clima
            $table->timestamps();
        });
    }
    

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronosticos');
    }
};

