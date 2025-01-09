<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\Lugar;
use App\Models\PronosticosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{

        public function store()
        {
            // Function to fetch and store the city data
            function obtenerCiudades($municipio) {
                $api_key = "a5777721902795125a7dc0474c5036a8";
                $language = "es";
                $units = "metric";
                $cnt = 10;
                $ciudad = new CiudadModel();
                
                $data = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$municipio&appid=$api_key&lang=$language&units=$units")->json();
                
                $ciudad->nombre = $data['name'];
                $ciudad->latitud = $data['coord']['lat'];
                $ciudad->longitud = $data['coord']['lon'];
                $ciudad->save();
                
                return $ciudad;
            }
    
            // Function to fetch and store weather forecast data
            function obtenerPronosticos($ciudad_id, $data) {
                $pronosticoPorHoras = $data['list'];
                
                foreach ($pronosticoPorHoras as $element) {
                    PronosticosModel::create([
                        'ciudad_id' => $ciudad_id,
                        'fecha_hora' => $element['dt_txt'],  // Corresponding to 'fecha_hora'
                        'fecha_unix' => $element['dt'],      // Corresponding to 'fecha_unix'
                        'temperatura' => $element['main']['temp'],
                        'temp_min' => $element['main']['temp_min'],
                        'temp_max' => $element['main']['temp_max'],
                        'sensacion_termica' => $element['main']['feels_like'],  // Changed to 'sensacion_termica'
                        'humedad' => $element['main']['humidity'],
                        'presion' => $element['main']['pressure'],
                        'viento' => $element['wind']['speed'],
                        'descripcion' => $element['weather'][0]['description'],
                        'nubes' => $element['clouds']['all'],
                        'amanecer' => $data['city']['sunrise'],
                        'atardecer' => $data['city']['sunset'],
                        'latitud' => $data['city']['coord']['lat'],
                        'longitud' => $data['city']['coord']['lon'],
                        'probabilidad_lluvia' => isset($element['rain']) ? $element['rain']['3h'] : 0,
                        'icono' => "http://openweathermap.org/img/wn/" . $element['weather'][0]['icon'] . "@4x.png",
                    ]);
                }
            }
    
            // Fetch the list of places
            $lugares = Lugar::all();
            CiudadModel::query()->delete();  // Deletes all cities
    
            foreach ($lugares as $lugar) {
                $ciudad = obtenerCiudades($lugar->nombre); // Get city data
                
                // Fetch and store the weather forecast for the city
                $api_key = "a5777721902795125a7dc0474c5036a8";
                $language = "es";
                $units = "metric";
                $data = Http::get("https://api.openweathermap.org/data/2.5/forecast?q=$lugar->nombre&appid=$api_key&lang=$language&units=$units")->json();
                
                // Store the weather forecast in the database
                obtenerPronosticos($ciudad->id, $data);
            }
        }
    }
    