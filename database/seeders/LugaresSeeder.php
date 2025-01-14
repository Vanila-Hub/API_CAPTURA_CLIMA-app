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
            "Bilbao",
            "Vitoria-Gasteiz",
            "San Sebastian",
            "Pamplona"
        ];

        foreach ($lugares as $lugar) {
            CiudadModel::insert([
                "nombre" => $lugar
            ]);
        }
    }
}
