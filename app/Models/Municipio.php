<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre'];
    protected $table = 'municipios'; // nombre de la tabla

    // Relación: un Municipio tiene muchas Colonias
    public function colonias()
    {
        return $this->hasMany(Colonia::class);
    }      
}
