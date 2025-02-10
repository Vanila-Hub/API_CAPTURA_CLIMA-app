<?php

namespace Database\Seeders;

use App\Models\CiudadModel;
use App\Models\PronosticoModel;
use Database\Factories\PronosticoModelFactory;
use Illuminate\Database\Seeder;



class PronosticosSeeder extends Seeder
{
    public function run(): void
    {
        // IDs de las ciudades (1 a x)
        $ciudades = CiudadModel::pluck('id');

        foreach ($ciudades as $ciudadId) {
            // Reiniciar el incremento de tiempo para cada ciudad
            PronosticoModelFactory::resetTimeIncrement();

            // Generar 17540 pronósticos para cada la ciudad 6 meses
            PronosticoModel::factory()->count(17540)->create([
                'ciudad_id' => $ciudadId,
            ]);
        }
    }
}
