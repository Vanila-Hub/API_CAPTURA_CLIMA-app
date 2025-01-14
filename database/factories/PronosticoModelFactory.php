<?php

namespace Database\Factories;

use App\Models\PronosticoModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PronosticosModel>
 */
class PronosticoModelFactory extends Factory
{
    protected $model = PronosticoModel::class;

    // Variable estática para controlar el incremento de tiempo
    private static $timeIncrement = 0;

    /**
     * Reinicia el incremento de tiempo.
     */
    public static function resetTimeIncrement()
    {
        self::$timeIncrement = 0;
    }

    public function definition(): array
    {
        // Fecha inicial: 1 de enero de 2024
        if (self::$timeIncrement == 0) {
            $date = Carbon::create(2024, 1, 1, 0, 0, 0);
        } else {
            // Incrementar el tiempo en 2 horas para cada nueva medición
            $date = Carbon::create(2024, 1, 1, 0, 0, 0)->addMinutes(self::$timeIncrement);
        }

        self::$timeIncrement += 15; // Incremento en horas

        // Determinar el rango de temperatura
        $temperatura = 15 + $this->faker->randomFloat(1, -2, 2); // Ajuste de temperatura

        // Descripciones posibles basadas en rangos de temperatura
        if ($temperatura <= 5) {
            $descripciones = [
                'cielo claro' => '01d',
                'nubes dispersas' => '03d',
                'nubes' => '04d',
                'lluvia ligera' => '10d',
            ];
        } elseif ($temperatura <= 15) {
            $descripciones = [
                'cielo claro' => '01d',
                'algo de nubes' => '02d',
                'nubes dispersas' => '03d',
                'muy nuboso' => '04d',
                'lluvia ligera' => '10d',
            ];
        } elseif ($temperatura <= 25) {
            $descripciones = [
                'cielo claro' => '01d',
                'algo de nubes' => '02d',
                'nubes dispersas' => '03d',
                'muy nuboso' => '04d',
                'lluvia ligera' => '10d',
                'nubes' => '04d',
            ];
        } else {
            $descripciones = [
                'cielo claro' => '01d',
                'algo de nubes' => '02d',
                'muy nuboso' => '04d',
                'lluvia ligera' => '10d',
                'tormenta' => '11d',
            ];
        }

        // Escoge una descripción aleatoria y su icono asociado
        $descripcion = $this->faker->randomElement(array_keys($descripciones));
        $icono = $descripciones[$descripcion];

        return [
            'ciudad_id' => $this->faker->numberBetween(1, 5), // 5 ciudades
            'fecha_hora' => $date,
            'fecha_unix' => $date->timestamp,
            'temperatura' => $temperatura,
            'temp_min' => 13 + $this->faker->randomFloat(1, -2, 0),
            'temp_max' => 17 + $this->faker->randomFloat(1, 0, 2),
            'sensacion_termica' => 15 + $this->faker->randomFloat(1, -2, 2),
            'humedad' => $this->faker->numberBetween(60, 80), // Ajuste de humedad
            'presion' => 1013 + $this->faker->randomFloat(1, -5, 5), // Ajuste de presión
            'viento' => $this->faker->randomFloat(1, 0, 20), // Ajuste de viento
            'descripcion' => $descripcion,
            'nubes' => $this->faker->numberBetween(0, 100),
            'amanecer' => $this->faker->unixTime(),
            'atardecer' => $this->faker->unixTime(),
            'latitud' => $this->faker->latitude(),
            'longitud' => $this->faker->longitude(),
            'probabilidad_lluvia' => $this->faker->randomFloat(2, 0, 0.3), // Probabilidad baja
            'icono' => $icono, // Asignar el código de icono
        ];
    }
}
