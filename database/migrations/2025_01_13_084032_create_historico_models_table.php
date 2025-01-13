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
        Schema::create('historico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciudad_id');
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('humedad', 5, 2);
            $table->decimal('nivel', 5, 2);
            $table->decimal('precipitacion', 5, 2);
            $table->decimal('temperatura', 5, 2);
            $table->timestamps();

            $table->foreign('ciudad_id')->references('id')->on('ciudades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico');
    }
};
