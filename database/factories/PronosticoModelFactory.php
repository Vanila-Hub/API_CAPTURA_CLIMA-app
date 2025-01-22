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

    private static $timeIncrement = 0;

    public static function resetTimeIncrement()
    {
        self::$timeIncrement = 0;
    }

    public function definition(): array
    {
        // Ajustar fecha para abarcar desde enero hasta junio (6 meses)
        $startDate = Carbon::create(2024, 1, 1, 0, 0, 0);
        $date = $startDate->copy()->addMinutes(self::$timeIncrement);

        self::$timeIncrement += 15; // Incremento de 15 minutos

        // Ajuste de temperatura según mes con variación realista entre -0.5°C y 0.5°C
        $month = $date->month;
        $temperatura = $this->getTemperatureForMonth($month) + $this->faker->randomFloat(1, -0.5, 0.5);  // Variación entre -0.5°C y 0.5°C

        // Descripciones posibles basadas en rangos de temperatura
        $descripciones = $this->getDescriptionsBasedOnTemperature($temperatura);
        $descripcion = $this->faker->randomElement(array_keys($descripciones));
        $icono = $descripciones[$descripcion];

        return [
            'ciudad_id' => $this->faker->numberBetween(1, 4),
            'fecha_hora' => $date,
            'fecha_unix' => $date->timestamp,
            'temperatura' => $temperatura,
            'temp_min' => $temperatura - $this->faker->randomFloat(1, 1, 3),
            'temp_max' => $temperatura + $this->faker->randomFloat(1, 1, 3),
            'sensacion_termica' => $temperatura + $this->faker->randomFloat(1, -1, 1),
            'humedad' => $this->faker->numberBetween(60, 80),
            'presion' => 1013 + $this->faker->randomFloat(1, -5, 5),
            'viento' => $this->faker->randomFloat(1, 0, 20),
            'descripcion' => $descripcion,
            'nubes' => $this->faker->numberBetween(0, 100),
            'amanecer' => $this->faker->unixTime(),
            'atardecer' => $this->faker->unixTime(),
            'probabilidad_lluvia' => $this->faker->randomFloat(2, 0, 0.3),
            'icono' => $icono,
        ];
    }

    // Método para obtener la temperatura promedio del mes
    private function getTemperatureForMonth($month)
    {
        $temperatureRanges = [
            1 => 5,  // Enero
            2 => 6,  // Febrero
            3 => 9,  // Marzo
            4 => 12, // Abril
            5 => 15, // Mayo
            6 => 19, // Junio
        ];

        return $temperatureRanges[$month] ?? 15;  // Valor por defecto si no se encuentra el mes
    }

    // Método para obtener las descripciones del clima basadas en la temperatura
    private function getDescriptionsBasedOnTemperature($temperatura)
    {
        if ($temperatura <= 5) {
            return [
                'cielo claro' => 'http://openweathermap.org/img/wn/01d@4x.png',
                'nubes dispersas' => 'http://openweathermap.org/img/wn/03d@4x.png',
                'nubes' => 'http://openweathermap.org/img/wn/04d@4x.png',
                'lluvia ligera' => 'http://openweathermap.org/img/wn/10d@4x.png',
            ];
        } elseif ($temperatura <= 15) {
            return [
                'cielo claro' => 'http://openweathermap.org/img/wn/01d@4x.png',
                'algo de nubes' => 'http://openweathermap.org/img/wn/02d@4x.png',
                'nubes dispersas' => 'http://openweathermap.org/img/wn/03d@4x.png',
                'muy nuboso' => 'http://openweathermap.org/img/wn/04d@4x.png',
                'lluvia ligera' => 'http://openweathermap.org/img/wn/10d@4x.png',
            ];
        } elseif ($temperatura <= 25) {
            return [
                'cielo claro' => 'http://openweathermap.org/img/wn/01d@4x.png',
                'algo de nubes' => 'http://openweathermap.org/img/wn/02d@4x.png',
                'nubes dispersas' => 'http://openweathermap.org/img/wn/03d@4x.png',
                'muy nuboso' => 'http://openweathermap.org/img/wn/04d@4x.png',
                'lluvia ligera' => 'http://openweathermap.org/img/wn/10d@4x.png',
                'nubes' => 'http://openweathermap.org/img/wn/04d@4x.png',
            ];
        } else {
            return [
                'cielo claro' => 'http://openweathermap.org/img/wn/01d@4x.png',
                'algo de nubes' => 'http://openweathermap.org/img/wn/02d@4x.png',
                'muy nuboso' => 'http://openweathermap.org/img/wn/04d@4x.png',
                'lluvia ligera' => 'http://openweathermap.org/img/wn/10d@4x.png',
                'tormenta' => 'http://openweathermap.org/img/wn/11d@4x.png',
            ];
        }
    }
}
