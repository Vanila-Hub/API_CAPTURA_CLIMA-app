<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\Lugar;
use App\Models\PronosticoModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{

    public function store()
    {
        // Function to fetch and store the city data
        function obtenerCiudades($municipio)
        {
            $api_key = "a5777721902795125a7dc0474c5036a8";
            $language = "es";
            $units = "metric";
            $cnt = 10;

            // Buscar la ciudad existente en la base de datos por su nombre
            $ciudad = CiudadModel::where('nombre', $municipio)->first();

            if (!$ciudad) {
                // Si no existe, crear una nueva ciudad
                $ciudad = new CiudadModel();
            }

            // Obtener datos de la API
            $data = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$municipio&appid=$api_key&lang=$language&units=$units")->json();

            // Actualizar los valores de la ciudad
            $ciudad->nombre = $data['name'];
            $ciudad->latitud = $data['coord']['lat'];
            $ciudad->longitud = $data['coord']['lon'];

            // Guardar la ciudad (insertar si no existe, actualizar si existe)
            $ciudad->save();

            return $ciudad;
        }

        // Function to fetch and store weather forecast data
        function obtenerPronosticos($ciudad_id, $data)
        {
            $pronosticoPorHoras = $data['list'];

            foreach ($pronosticoPorHoras as $element) {
                PronosticoModel::create([
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



        $lugares = CiudadModel::all();  // Deletes all cities


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

    public function index()
    {
        $ciudades = CiudadModel::all();
        return response()->json($ciudades);
    }
    public function show($ciudad_nombre, $fecha_inicio, $fecha_fin)
    {
    // Verificar si la ciudad existe
    $ciudad = CiudadModel::where('nombre', $ciudad_nombre)->first();
    if (!$ciudad) {
        return response()->json(['message' => 'Ciudad no encontrada con el nombre: ' . $ciudad_nombre], 404);
    }


        $pronosticos = PronosticoModel::join('ciudades as C', 'pronosticos.ciudad_id', '=', 'C.id')
        ->where('C.id', $ciudad->id)
        ->whereBetween('pronosticos.fecha_hora', [$fecha_inicio, $fecha_fin])
        ->orderBy('pronosticos.fecha_hora', 'ASC')
        ->get(['C.nombre', 'pronosticos.*']);
    
      
        return response()->json($pronosticos);
    }
}
