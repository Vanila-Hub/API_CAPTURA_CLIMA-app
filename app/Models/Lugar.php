<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    protected $table = 'lugares';   // El nombre de la tabla no será "articles" sino "articulos"
    protected $primaryKey = 'id'; // La clave primaria no será "id" sino "id_art"
    // Campos de la tabla en los que se permite la ASIGNACIÓN MASIVA
    // (más adelante veremos qué es esto)
    protected $fillable = array('nombre');
}
