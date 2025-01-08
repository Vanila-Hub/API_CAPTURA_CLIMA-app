<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{
    //
    public function store()
    {
        $lugares = Lugar::all();

        function obtenerPronostico($municipio)
        {
            $api_key = "a5777721902795125a7dc0474c5036a8";
            $language = "es";
            $units = "metric";
            $cnt = 40;
            $ciudad = new CiudadModel();

            $data = Http::get("https://api.openweathermap.org/data/2.5/forecast?q=$municipio&appid=$api_key&lang=$language&units=$units&cnt=$cnt")->json();
            $pronosticoPorHoras = collect($data['list'])->map(function ($element) use ($data) {
                return [
                    'fecha' => $element['dt_txt'],
                    'fecha_unix' => $element['dt'],
                    'temperatura' => $element['main']['temp'],
                    'temp_min' => $element['main']['temp_min'],
                    'temp_max' => $element['main']['temp_max'],
                    'presion' => $element['main']['pressure'],
                    'humedad' => $element['main']['humidity'],
                    'nubes' => $element['clouds']['all'],
                    'amanecer' => $data['city']['sunrise'],
                    'atardecer' => $data['city']['sunset'],
                    'viento' => $element['wind']['speed'],
                    'probabilidadDeLluvia' => isset($element['rain']) ? $element['rain']['3h'] : 0,
                    'latitud' => $data['city']['coord']['lat'],
                    'longitud' => $data['city']['coord']['lon'],
                    'descripcion' => $element['weather'][0]['description'],
                    'sensacionTermica' => $element['main']['feels_like'],
                    'icono' => 'http://openweathermap.org/img/wn/' . $element['weather'][0]['icon'] . '@2x.png',
                    'lluvia' => isset($element['rain']) ? $element['rain']['3h'] : 0,
                ];
            });
            $ciudad->pronosticos()->saveMany($pronosticoPorHoras);
            
        }
    }
}
