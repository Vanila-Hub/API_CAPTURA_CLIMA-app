<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PronosticosModel extends Model
{
    //
    protected $table = 'pronosticos';   // El nombre de la tabla no será "articles" sino "articulos"
    protected $primaryKey = 'id'; // La clave primaria no será "id" sino "id_art"
    protected $fillable = array(
        'ciudad_id', 
        'fecha_hora', 
        'fecha_unix', 
        'temperatura', 
        'temp_min', 
        'temp_max', 
        'sensacion_termica', 
        'humedad', 
        'presion', 
        'viento', 
        'descripcion', 
        'nubes', 
        'amanecer', 
        'atardecer', 
        'latitud', 
        'longitud', 
        'probabilidad_lluvia', 
        'icono'
    ); // Campos de la tabla en los que se permite la ASIGNACIÓN MASIVA (más adelante veremos qué es esto)

    public function ciudad()
    {
        return $this->belongsTo(CiudadModel::class, 'ciudad_id'); // 'ciudad_id' es la clave foránea en la tabla 'lugares'
    }
}
