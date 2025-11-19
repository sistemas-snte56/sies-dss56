<?php

namespace Database\Seeders;

use App\Models\Colonia;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ColoniasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Importar localidades
        $colonias = array_map('str_getcsv', file(database_path('seeders/data/colonias.csv')));
        foreach ($colonias as $index => $row) {
            if ($index === 0) continue; // omitir cabecera
            Colonia::create([
                'id' => $row[0],
                'municipio_id' => $row[1],
                'nombre' => $row[2],
                'codigo_postal' => $row[3]
            ]);
        }
    }
}
