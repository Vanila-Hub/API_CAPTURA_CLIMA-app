/**
 * Clase CiudadesSeeder
 * 
 * Seeder de base de datos para poblar la tabla de ciudades con datos iniciales.
 * Este seeder inserta registros de ciudades predefinidas en la base de datos
 * con sus coordenadas geográficas (latitud y longitud).
 * 
 * Ciudades incluidas:
 * - Bilbao
 * - Vitoria-Gasteiz
 * - San Sebastian
 * - Pamplona
 * - Irun
 *
 * @package Database\Seeders
 */
<?php

namespace Database\Seeders;

use App\Models\CiudadModel;
use App\Models\Lugar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CiudadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ciudades = [
            [
            "nombre" => "Bilbao",
            "latitud" => "43.262700",
            "longitud" => "-2.925300",
            ],
            [
            "nombre" => "Vitoria-Gasteiz",
            "latitud" => "42.850000",
            "longitud" => "-2.666700",
            ],
            [
            "nombre" => "San Sebastian",
            "latitud" => "43.312800",
            "longitud" => "-1.975000",
            ],
            [
            "nombre" => "Pamplona",
            "latitud" => "42.816900",
            "longitud" => "-1.643200",
            ],
            [
            "nombre" => "Irun",
            "latitud" => "43.339000",
            "longitud" => "-1.789400",
            ]
        ];

        foreach ($ciudades as $ciudad) {
            CiudadModel::insert($ciudad);
        }
    }
}
