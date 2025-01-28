<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\PronosticoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PronosticoModelControler extends Controller
{
    //
    public function guardarPronosticoActualApi($ciudad_id, $data)
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

    public function obtenerPronosticoPorciudad($ciudad_nombre)
    {
        // Verificar si la ciudad existe
        $ciudad = CiudadModel::where('nombre', $ciudad_nombre)->first();
        if (!$ciudad) {
            return response()->json(['message' => 'Ciudad no encontrada con el nombre: ' . $ciudad_nombre], 404);
        }

        // Leer la clave API desde el archivo .env
        $API_key = env('OPENWEATHER_API_KEY');
        $language = "es";
        $units = "metric";
        $cnt = 32; // Cambiado a 32 para obtener datos de aquí a 4 días

        $api = "https://api.openweathermap.org/data/2.5/forecast?lat={$ciudad->latitud}&lon={$ciudad->longitud}&lang={$language}&units={$units}&appid={$API_key}&cnt={$cnt}";
        $response = Http::get($api);
        $data = $response->json();

        $pronosticoPorHoras = array_map(function ($element) use ($data) {
            return [
            'fecha' => $element['dt_txt'],
            'fecha_unix' => $element['dt'],
            'temperatura' => round($element['main']['temp']),
            'temp_min' => round($element['main']['temp_min']),
            'temp_max' => round($element['main']['temp_max']),
            'presion' => round($element['main']['pressure']),
            'humedad' => round($element['main']['humidity']),
            'nubes' => round($element['clouds']['all']),
            'amanecer' => $data['city']['sunrise'],
            'atardecer' => $data['city']['sunset'],
            'viento' => round($element['wind']['speed']),
            'probabilidadDeLluvia' => round(isset($element['rain']['3h']) ? $element['rain']['3h'] : 0),
            'descripcion' => $element['weather'][0]['description'],
            'sensacionTermica' => round($element['main']['feels_like']),
            'icono' => "http://openweathermap.org/img/wn/{$element['weather'][0]['icon']}@2x.png",
            'lluvia' => round(isset($element['rain']['3h']) ? $element['rain']['3h'] : 0),
            ];
        }, $data['list']);

        return response()->json($pronosticoPorHoras);
    }
}
