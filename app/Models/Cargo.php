<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'activo',
    ];
    
    public function participantes()
    {
        return $this->belongsToMany(Participante::class)->withTimestamps();
    }
}
