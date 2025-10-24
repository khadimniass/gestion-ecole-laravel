<?php

namespace Database\Seeders;

use App\Models\AnneeUniversitaire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnneeUniversitaireSeeder extends Seeder
{
    public function run()
    {
        $annees = [
            [
                'annee' => '2023-2024',
                'date_debut' => '2023-10-01',
                'date_fin' => '2024-07-31',
                'active' => false,
            ],
            [
                'annee' => '2024-2025',
                'date_debut' => '2024-10-01',
                'date_fin' => '2025-07-31',
                'active' => true,
            ],
        ];

        foreach ($annees as $annee) {
            AnneeUniversitaire::create($annee);
        }
    }
}
