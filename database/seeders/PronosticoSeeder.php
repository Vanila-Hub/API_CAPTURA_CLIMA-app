<?php

namespace Database\Seeders;

use App\Models\CiudadModel;
use App\Models\PronosticoModel;
use Database\Factories\PronosticoModelFactory;
use Illuminate\Database\Seeder;



class PronosticoSeeder extends Seeder
{
    public function run(): void
    {
        // IDs de las ciudades (1 a 5)
        $ciudades = CiudadModel::pluck('id');

        foreach ($ciudades as $ciudadId) {
            // Reiniciar el incremento de tiempo para cada ciudad
            PronosticoModelFactory::resetTimeIncrement();

            // Generar 2196 pronósticos para la ciudad
            PronosticoModel::factory()->count(2196)->create([
                'ciudad_id' => $ciudadId,
            ]);
        }
    }
}
