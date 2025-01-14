<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoModel extends Model
{
    use HasFactory;
    protected $table = 'historico';   // El nombre de la tabla no será "articles" sino "articulos"
    protected $primaryKey = 'id'; // La clave primaria no será "id" sino "id_art"

    protected $fillable = [
        'ciudad_id',
        'fecha',
        'hora',
        'humedad',
        'nivel',
        'precipitacion',
        'temperatura'
    ];

    public function ciudad()
    {
        return $this->belongsTo(CiudadModel::class, 'ciudad_id'); // 'ciudad_id' es la clave foránea en la tabla 'lugares'
    }
}
