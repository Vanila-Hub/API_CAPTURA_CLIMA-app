<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\Lugar;
use App\Models\PronosticoModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{

    public function store()
    {
        // Obtener todas las ciudades desde la base de datos
        $lugares = CiudadModel::all();
    
        foreach ($lugares as $lugar) {
            // Obtener los datos de la ciudad (ya está en la base de datos)
            $ciudad = $lugar; // Ya tienes la ciudad, no necesitas volver a obtenerla.
    
            // Obtener el pronóstico del clima actual usando la API de OpenWeather
            $api_key = "a5777721902795125a7dc0474c5036a8";
            $language = "es";
            $units = "metric";
            
            $data = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$lugar->latitud}&lon={$lugar->longitud}&lang={$language}&units={$units}&appid={$api_key}")->json();
    
            // Verificar si la respuesta contiene datos
            if (isset($data['weather'])) {
                // Llamar a la función para almacenar el pronóstico en la base de datos
                $this->obtenerPronosticoActual($ciudad->id, $data);
            }
        }
    
        return response()->json(['message' => 'Datos del clima almacenados correctamente.']);
    }
    
    public function obtenerPronosticoActual($ciudad_id, $data)
    {
        // Almacenar el pronóstico actual en la base de datos
        $pronosticoModel = new PronosticoModel();
        $pronosticoModel->ciudad_id = $ciudad_id;
        $pronosticoModel->fecha_hora = date('Y-m-d H:i:s', $data['dt']);
        $pronosticoModel->fecha_unix = $data['dt'];
        $pronosticoModel->temperatura = $data['main']['temp'];
        $pronosticoModel->temp_min = $data['main']['temp_min'];
        $pronosticoModel->temp_max = $data['main']['temp_max'];
        $pronosticoModel->sensacion_termica = $data['main']['feels_like'];
        $pronosticoModel->humedad = $data['main']['humidity'];
        $pronosticoModel->presion = $data['main']['pressure'];
        $pronosticoModel->viento = $data['wind']['speed'];
        $pronosticoModel->descripcion = $data['weather'][0]['description'];
        $pronosticoModel->nubes = $data['clouds']['all'];
        $pronosticoModel->amanecer = $data['sys']['sunrise'];
        $pronosticoModel->atardecer = $data['sys']['sunset'];
        $pronosticoModel->probabilidad_lluvia = isset($data['rain']['1h']) ? $data['rain']['1h'] : 0;
        $pronosticoModel->icono = "http://openweathermap.org/img/wn/{$data['weather'][0]['icon']}@4x.png";
        $pronosticoModel->save();
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
