<?php
namespace Database\Factories;

use App\Models\CiudadModel;
use App\Models\HistoricoModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class HistoricoFactory extends Factory
{
    protected $model = HistoricoModel::class;

    // Variable estática para controlar el incremento de tiempo
    private static $timeIncrement = 0;

    public static function resetTimeIncrement()
    {
        self::$timeIncrement = 0;
    }

    public function definition()
    {
        $ciudadId = CiudadModel::inRandomOrder()->first()->id;

        static $baseTemperature = 15;

        $temperatureChange = rand(-2, 2);
        $temperature = $baseTemperature + $temperatureChange;

        $humidityChange = rand(-2, 2);
        $humidity = max(60, min(80, 70 + $humidityChange));

        $precipitationChange = rand(0, 1);
        $precipitation = $precipitationChange == 0 ? 0 : rand(1, 5);

        $level = $temperature * 0.5;

        $date = Carbon::create(2024, 1, 1, 0, 0, 0)->addMinutes(self::$timeIncrement);
        self::$timeIncrement += 15;

        return [
            'ciudad_id' => $ciudadId,
            'fecha' => $date->toDateString(),
            'hora' => $date->toTimeString(),
            'humedad' => $humidity,
            'nivel' => $level,
            'precipitacion' => $precipitation,
            'temperatura' => $temperature,
        ];
    }
}
