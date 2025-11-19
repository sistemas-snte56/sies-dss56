<?php

namespace Database\Seeders;

use App\Models\NivelAcademico;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NivelesAcademicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            "Primaria",
            "Secundaria",
            "Bachiller",
            "Normal Básica",
            "Licenciatura",
            "Especialidad",
            "Maestría",
            "Posgrado",
            "Doctorado",
            "Universidad",
        ];

        foreach ($niveles as $nivel) {
            NivelAcademico::create(['nombre' => $nivel]);
        }        
    }
}
