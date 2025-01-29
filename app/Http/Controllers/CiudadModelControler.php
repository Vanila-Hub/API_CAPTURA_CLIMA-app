<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{

    public function store(Request $request)
    {
        $ciudad = CiudadModel::create($request->all());
        return response()->json(['message' => 'Ciudad creada correctamente', 'ciudad' => $ciudad], 201);
    }
    public function generate_cities_forecast()
    {
                // Obtener todas las ciudades desde la base de datos
                $ciudades = CiudadModel::all();
    
                foreach ($ciudades as $ciudad) {
                    
                    // Obtener el pronóstico del clima actual usando la API de OpenWeather
                    $api_key = env('OPENWEATHER_API_KEY');
                    $language = "es";
                    $units = "metric";
                    
                    $data = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$ciudad->latitud}&lon={$ciudad->longitud}&lang={$language}&units={$units}&appid={$api_key}")->json();
            
                    // Verificar si la respuesta contiene datos
                    if (isset($data['weather'])) {
                        $pronosticoModel = new PronosticoModelControler();
                        // Llamar a la función para almacenar el pronóstico en la base de datos
                        $pronosticoModel->guardarPronosticoActualApi($ciudad->id, $data);
                    }
                }
            
                return response()->json(['message' => 'Datos del clima almacenados correctamente.'], 200);
    }
    


    public function index()
    {
        $ciudades = CiudadModel::all();
        return response()->json($ciudades);
    }
    public function show($id)
    {
        $ciudad = CiudadModel::find($id);
        if (is_null($ciudad)) {
            return response()->json(['message' => 'Ciudad no encontrada'], 404);
        }
        return response()->json($ciudad);
    }

    public function update(Request $request, $id)
    {
        $ciudad = CiudadModel::find($id);
        if (is_null($ciudad)) {
            return response()->json(['message' => 'Ciudad no encontrada'], 404);
        }
        $ciudad->update($request->all());
        return response()->json(['message' => 'Ciudad actualizada correctamente']);
    }

    public function destroy($id)
    {
        $ciudad = CiudadModel::find($id);
        if (is_null($ciudad)) {
            return response()->json(['message' => 'Ciudad no encontrada'], 404);
        }
        $ciudad->delete();
        return response()->json(['message' => 'Ciudad eliminada correctamente']);
    }

}
