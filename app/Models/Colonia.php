<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    use HasFactory;

    protected $table = 'colonias'; // nombre de la tabla
    protected $fillable = ['id', 'municipio_id', 'nombre', 'codigo_postal'];
    public $incrementing = false;

    // Relación: una Localidad pertenece a un Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }     
}
