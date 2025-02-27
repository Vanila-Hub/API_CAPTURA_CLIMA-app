<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiudadModel extends Model
{
    protected $table = 'ciudades'; 
    protected $primaryKey = 'id';  
    protected $fillable = array('nombre', 'latitud','longitud'); // Campos de la tabla en los que se permite la ASIGNACIÓN MASIVA

       /**
     * Definir la relación de uno a muchos con el modelo pronostico.
     */
    public function lugares()
    {
        return $this->hasMany(PronosticoModel::class, 'ciudad_id'); // 'ciudad_id' es la clave foránea en la tabla 'lugares'
    }
}
