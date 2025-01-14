<?php

namespace Database\Seeders;

use App\Models\Ciudad;  // Asegúrate de que sea el nombre correcto de tu modelo Ciudad
use App\Models\CiudadModel;
use Illuminate\Database\Seeder;
use App\Models\Historico;  // Asegúrate de que sea el nombre correcto de tu modelo Historico
use App\Models\HistoricoModel;
use Database\Factories\HistoricoFactory; // Asegúrate de tener el factory de Historico

class HistoricoModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definimos los IDs de las ciudades
        $ciudades_ = CiudadModel::all();  // Asegúrate de que 'Ciudad' sea el nombre correcto de tu modelo
        $ciudades = $ciudades_->pluck('id'); // Ajusta según el nombre de la columna de 'id' en la tabla 'ciudades'

        // Iteramos por cada ciudad
        foreach ($ciudades as $ciudadId) {

            // Reiniciar el incremento de tiempo antes de comenzar con cada ciudad
            HistoricoFactory::resetTimeIncrement();

            // Creamos 2196 mediciones para cada ciudad
            HistoricoModel::factory()->count(2196)->create([
                'ciudad_id' => $ciudadId,  // Cambiar el 'ciudad_id' según la ciudad
            ]);
        }
    }
}
