<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cargos = [
            "INTEGRANTE",
            "INTEGRANTE DEL COMITÉ EJECUTIVO SECCIONAL",
            "INTEGRANTE DELEGACIONAL",
            "OTRO",
            "REPRESENTANTE DE CENTRO DE TRABAJO",
            "SECRETARÍA GENERAL",
            "SECRETARÍA GENERAL - JUBILADOS",
            "SECRETARÍA DE CULTURA Y RECREACIÓN - JUBILADOS",
            "SECRETARÍA DE ESCALAFÓN Y PROMOCIÓN",
            "SECRETARÍA DE FINANZAS",
            "SECRETARÍA DE FINANZAS - JUBILADOS",
            "SECRETARÍA DE ORGANIZACIÓN",
            "SECRETARÍA DE ORGANIZACIÓN - JUBILADOS",
            "SECRETARÍA DE ORIENTACIÓN IDEOLÓGICA—SINDICAL",
            "SECRETARÍA DE PREVISIÓN Y ASISTENCIA SOCIAL",
            "SECRETARÍA DE TRABAJO Y CONFLICTOS",
            "SECRETARÍA DE VINCULACION SOCIAL Y PROGRAMAS PRODUCTIVOS - JUBILADOS",
        ];

        foreach ($cargos as $cargo) {
            Cargo::create(['nombre' => $cargo]);
        }
    }
}
