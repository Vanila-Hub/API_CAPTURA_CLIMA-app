<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pronosticos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciudad_id');
            $table->foreign('ciudad_id')->references('id')->on('ciudades')->onDelete('cascade');
            $table->timestamp('fecha_hora');  // Changed to 'fecha_hora'
            $table->integer('fecha_unix');
            $table->decimal('temperatura', 5, 2);
            $table->decimal('temp_min', 5, 2);
            $table->decimal('temp_max', 5, 2);
            $table->decimal('sensacion_termica', 5, 2);  // Changed to 'sensacion_termica'
            $table->integer('humedad');
            $table->integer('presion');
            $table->integer('viento');
            $table->string('descripcion');
            $table->integer('nubes');
            $table->integer('amanecer');
            $table->integer('atardecer');
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->decimal('probabilidad_lluvia', 5, 2)->default(0);  // Changed to 'probabilidad_lluvia'
            $table->string('icono');
            $table->timestamps();
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
