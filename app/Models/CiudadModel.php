<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiudadModel extends Model
{
    protected $table = 'ciudades';   // El nombre de la tabla no será "articles" sino "articulos"
    protected $primaryKey = 'id'; // La clave primaria no será "id" sino "id_art"
    protected $fillable = array('nombre', 'latitud','longitud'); // Campos de la tabla en los que se permite la ASIGNACIÓN MASIVA (más adelante veremos qué es esto)
}
