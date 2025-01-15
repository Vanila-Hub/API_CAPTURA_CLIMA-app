<?php

namespace Database\Seeders;

use App\Models\CiudadModel;
use App\Models\Lugar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LugaresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $lugares = [
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
            ]
        ];

        foreach ($lugares as $lugar) {
            CiudadModel::insert($lugar);
        }
    }
}
