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
            "ZESTOA",
            "HERNANI",
            "AMOREBIETA-ETXANO",
            "AZKOITIA",
            "BILBAO",
            "ARRIGORRIAGA",
            "ASTIGARRAGA",
            "VITORIA-GASTEIZ",
            "OIARTZUN",
            "LANTARON",
            "DONOSTIA",
            "ZUIA",
            "OROZKO",
            "ERRENTERIA",
            "BEASAIN",
            "ZUMAIA",
            "LASARTE-ORIA",
            "ERANDIO",
            "ORTUELLA",
            "TOLOSA",
            "LEGAZPI",
            "ALONSOTEGI",
            "BASAURI",
            "MARKINA-XEMEIN",
            "MUSKIZ",
            "BARAKALDO",
            "LEIOA",
            "LEGUTIO",
            "PASAIA",
            "ZALLA",
            "PORTUGALETE",
            "BERASTEGI",
            "ETXEBARRI",
            "IURRETA",
            "LEMOA",
            "ABADIÑO",
            "BAZTAN",
            "ELGOIBAR",
            "DURANGO",
            "DEBA",
            "MUTILOA",
            "ELGETA",
            "GETARIA",
            "SALVATIERRA",
            "ORDIZIA",
            "EIBAR",
            "ZARAUTZ",
            "BEDIA",
            "USURBIL",
            "BERNEDO",
            "LAZKAO",
            "GALDAKAO",
            "UGAO-MIRABALLES",
            "URRETXU",
            "IDIAZABAL",
            "ONDARROA",
            "BERRIZ",
            "ZAMUDIO",
            "IGORRE",
            "MUNGIA",
            "BERGARA",
            "OÑATI",
            "IRUN",
            "ORIO",
            "ARRASATE",
            "HONDARRIBIA"
        ];
        foreach ($lugares as $lugar) {
            Lugar::insert([
                "nombre" => $lugar
            ]);
        }
    }
}
