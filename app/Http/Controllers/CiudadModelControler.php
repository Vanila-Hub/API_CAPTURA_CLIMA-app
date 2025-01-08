<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\Lugar;
use App\Models\PronosticosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{
    //
    public function store()
    {
        $lugares = Lugar::all();

        function obtenerCiudades($municipio){
            $api_key = "a5777721902795125a7dc0474c5036a8";
            $language = "es";
            $units = "metric";
            $cnt = 10;
            $ciudad = new CiudadModel();

            $data = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$municipio&appid=$api_key&lang=$language&units=$units&cnt=$cnt")->json();

            $ciudad->nombre = $data['city']['name'];
            $ciudad->latitud = $data['city']['coord']['lat'];
            $ciudad->longitud = $data['city']['coord']['lon'];
            $ciudad->save();

            return $ciudad;
        }

        foreach ($lugares as $lugar) {
            obtenerCiudades($lugar->nombre);
        }
    }
}
