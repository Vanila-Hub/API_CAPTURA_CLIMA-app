<?php

namespace Database\Seeders;

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
            "BILBAO",
            "VITORIA-GASTEIZ",
            "DONOSTIA",
            "PAMPLONA"
        ];
        foreach ($lugares as $lugar) {
            Lugar::insert([
                "nombre" => $lugar
            ]);
        }
    }
}
