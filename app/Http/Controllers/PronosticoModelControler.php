<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use App\Models\PronosticoModel;
use Illuminate\Support\Facades\Http;

class PronosticoModelControler extends Controller
{
    /**
     * @OA\Post(
     *     path="/pronostico/guardar",
     *     tags={"Pronóstico"},
     *     summary="Save weather forecast into the database",
     *     @OA\Parameter(
     *         name="ciudad_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Forecast saved successfully")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/clima/{ciudad_nombre}/{fecha_inicio}/{fecha_fin}",
     *     tags={"Pronóstico"},
     *     summary="Get forecast by city name and date range YYY-MM-DD HH:MM:SS",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="ciudad_nombre",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="fecha_inicio",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="fecha_fin",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(response=200, description="Forecast data retrieved"),
     *     @OA\Response(response=404, description="City not found")
     * )
     */
    public function show($ciudad_nombre, $fecha_inicio, $fecha_fin)
    {
        // Verificar si la ciudad existe
        $ciudad = CiudadModel::where('nombre', $ciudad_nombre)->first();
        if (!$ciudad) {
            return response()->json(['message' => 'datos de la ciudad no encontrada: ' . $ciudad_nombre], 404);
        }
        $pronosticos = PronosticoModel::join('ciudades as C', 'pronosticos.ciudad_id', '=', 'C.id')
            ->where('C.id', $ciudad->id)
            ->whereBetween('pronosticos.fecha_hora', [$fecha_inicio, $fecha_fin])
            ->orderBy('pronosticos.fecha_hora', 'DESC')
            ->limit(1)
            ->get(['C.nombre', 'pronosticos.*']);

        return response()->json($pronosticos);
    }

    /**
     * @OA\Get(
     *     path="/pronostico/{ciudad_nombre}",
     *     tags={"Pronóstico"},
     *     summary="Get forecast 4 days in the future by city name",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="ciudad_nombre",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Forecast data retrieved"),
     *     @OA\Response(response=404, description="City not found"),
     *     @OA\Response(response=500, description="API Error")
     * )
     */
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
            'latitud' => $data['city']['coord']['lat'],
            'longitud' => $data['city']['coord']['lon'],
            'descripcion' => $element['weather'][0]['description'],
            'sensacionTermica' => round($element['main']['feels_like']),
            'icono' => "http://openweathermap.org/img/wn/{$element['weather'][0]['icon']}@2x.png",
            'lluvia' => round(isset($element['rain']['3h']) ? $element['rain']['3h'] : 0),
            ];
        }, $data['list']);

        return response()->json($pronosticoPorHoras);
    }

    /**
     * @OA\Get(
     *     path="/historico/{ciudad_nombre}",
     *     tags={"Pronóstico"},
     *     summary="Get historical forecast data by city name and date range",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="ciudad_nombre",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="fecha_inicio",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="fecha_fin",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(response=200, description="Historical forecast data retrieved"),
     *     @OA\Response(response=404, description="City not found"),
     *     @OA\Response(response=400, description="Bad Request - Invalid date range"),
     *     @OA\Response(response=500, description="API Error")
     * )
     */

     public function historic_data($ciudad_nombre, $fecha_inicio, $fecha_fin)
     {
         // Verificar si la ciudad existe
         $ciudad = CiudadModel::where('nombre', $ciudad_nombre)->first();
         if (!$ciudad) {
             return response()->json(['message' => 'Ciudad no encontrada con el nombre: ' . $ciudad_nombre], 404);
         }
 
         // Validate date range (important!)
         $fecha_inicio_obj = \DateTime::createFromFormat('Y-m-d', $fecha_inicio);
         $fecha_fin_obj = \DateTime::createFromFormat('Y-m-d', $fecha_fin);
 
         if (!$fecha_inicio_obj || !$fecha_fin_obj || $fecha_inicio_obj > $fecha_fin_obj) {
             return response()->json(['message' => 'Rango de fechas inválido.  Asegúrese que las fechas estén en formato YYYY-MM-DD y que la fecha de inicio sea menor o igual a la fecha final.'], 400);
         }
 
 
         $pronosticos = PronosticoModel::join('ciudades as C', 'pronosticos.ciudad_id', '=', 'C.id')
             ->where('C.id', $ciudad->id)
             ->whereBetween('pronosticos.fecha_hora', [$fecha_inicio, $fecha_fin])
             ->orderBy('pronosticos.fecha_hora', 'ASC')
             ->get(['C.nombre', 'pronosticos.*']);
 
         return response()->json($pronosticos, 200);
     }
}
