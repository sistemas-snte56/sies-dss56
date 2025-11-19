<?php

namespace Database\Seeders;

use App\Models\Municipio;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MunicipiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Importar municipios
        $municipios = array_map('str_getcsv', file(database_path('seeders/data/municipios.csv')));
        foreach ($municipios as $index => $row) {
            if ($index === 0) continue; // omitir cabecera
            Municipio::create([
                'id' => $row[0],
                'nombre' => ($row[1])
            ]);
        }
    }
}
