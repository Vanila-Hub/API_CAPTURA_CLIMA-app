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
                $pronosticoModel = new PronosticoModelControler();
                // Llamar a la función para almacenar el pronóstico en la base de datos
                $pronosticoModel->guardarPronosticoActualApi($ciudad->id, $data);
            }
        }
    
        return response()->json(['message' => 'Datos del clima almacenados correctamente.']);
    }
    


    public function index()
    {
        $ciudades = CiudadModel::all();
        return response()->json($ciudades);
    }

}
