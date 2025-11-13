<?php

namespace Database\Seeders;

use App\Models\Delegacion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DelegacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/delegaciones.csv');

        if (!file_exists($path)) {
            $this->command->warn('El archivo delegacions.csv no fue encontrado.');
            return;
        }

        $delegacions = array_map('str_getcsv', file($path));

        foreach ($delegacions as $index => $row) {
            if ($index === 0) continue; // Omitir cabecera

            Delegacion::updateOrCreate(
                ['id' => $row[0]],
                [
                    'region_id' => $row[1],
                    'delegacion' => $row[2],
                    'nivel' => $row[3],
                    'sede' => $row[4],
                ]
            );
        }

        $this->command->info('Delegaciones importadas correctamente.');
    }
}
