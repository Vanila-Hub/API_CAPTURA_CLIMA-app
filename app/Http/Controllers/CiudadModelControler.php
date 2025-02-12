<?php

namespace App\Http\Controllers;

use App\Models\CiudadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CiudadModelControler extends Controller
{
    /**
     * @OA\Post(
     *     path="/ciudades",
     *     tags={"Ciudades"},
     *     summary="Create new city",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","latitud","longitud"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="latitud", type="number", format="float"),
     *             @OA\Property(property="longitud", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=201, description="City created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $ciudad = CiudadModel::create($request->all());
        return response()->json(['message' => 'Ciudad creada correctamente', 'ciudad' => $ciudad], 201);
    }

    /**
     * @OA\Get(
     *     path="/ciudades/forecast",
     *     tags={"Ciudades"},
     *     summary="Generate Weather data retrieve from open weather api for all cities and store them in the database",
     *     @OA\Response(response=200, description="Weather data stored successfully"),
     *     @OA\Response(response=500, description="Error generating forecast")
     * )
     */
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
            else{
                return response()->json(['message' => 'Error al generar el pronóstico'], 500);
            }
        }
    
        return response()->json(['message' => 'Datos de pronostico almacenados correctamente en la base de datos'], 200);
    }

    /**
     * @OA\Get(
     *     path="/ciudades",
     *     tags={"Ciudades"},
     *     summary="Get all cities",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="List of all cities")
     * )
     */
    public function index()
    {
        $ciudades = CiudadModel::all();
        return response()->json($ciudades);
    }

    /**
     * @OA\Get(
     *     path="/ciudades/{id}",
     *     tags={"Ciudades"},
     *     summary="Get city by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="City ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="City details"),
     *     @OA\Response(response=404, description="City not found")
     * )
     */
    public function show($id)
    {
        $ciudad = CiudadModel::find($id);
        if (is_null($ciudad)) {
            return response()->json(['message' => 'Ciudad no encontrada'], 404);
        }
        return response()->json($ciudad);
    }

    /**
     * @OA\Put(
     *     path="/ciudades/{id}",
     *     tags={"Ciudades"},
     *     summary="Update city",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","latitud","longitud"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="latitud", type="number", format="float"),
     *             @OA\Property(property="longitud", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=200, description="City updated successfully"),
     *     @OA\Response(response=404, description="City not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $ciudad = CiudadModel::find($id);
        if (is_null($ciudad)) {
            return response()->json(['message' => 'Ciudad no encontrada'], 404);
        }
        $ciudad->update($request->all());
        return response()->json(['message' => 'Ciudad actualizada correctamente']);
    }

    /**
     * @OA\Delete(
     *     path="/ciudades/{id}",
     *     tags={"Ciudades"},
     *     summary="Delete city",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="City deleted successfully"),
     *     @OA\Response(response=404, description="City not found")
     * )
     */
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
