<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegacion extends Model
{
    use HasFactory;

    protected $table = 'delegaciones';

    protected $fillable = ['region_id', 'delegacion', 'nivel', 'sede'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // public function participantes()
    // {
    //     return $this->hasMany(Participante::class);
    // }      
}
