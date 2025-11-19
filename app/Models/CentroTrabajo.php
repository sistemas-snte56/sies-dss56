<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroTrabajo extends Model
{
    use HasFactory;

    protected $table = 'centros_trabajo';
  

    protected $fillable = [
        'nombre_ct',
        'clave_ct',
        'codigo_postal',
        'colonia_id',
        'calle',
        'numero_exterior',
        'activo',
    ];
      
    public function colonia()
    {
        return $this->belongsTo(Colonia::class);
    }      
}
