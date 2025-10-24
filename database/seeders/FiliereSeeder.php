<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run()
    {
        $filieres = [
            [
                'nom' => 'Informatique de Gestion',
                'code' => 'IG',
                'niveau' => 'licence',
                'departement' => 'Informatique',
                'description' => 'Formation en informatique appliquée à la gestion des entreprises',
            ],
            [
                'nom' => 'Génie Logiciel',
                'code' => 'GL',
                'niveau' => 'licence',
                'departement' => 'Informatique',
                'description' => 'Formation en développement et architecture logicielle',
            ],
            [
                'nom' => 'Réseaux et Télécommunications',
                'code' => 'RT',
                'niveau' => 'licence',
                'departement' => 'Informatique',
                'description' => 'Formation en administration réseau et télécommunications',
            ],
            [
                'nom' => 'Master Informatique',
                'code' => 'MI',
                'niveau' => 'master',
                'departement' => 'Informatique',
                'description' => 'Master en informatique avancée',
            ],
            [
                'nom' => 'Master Data Science',
                'code' => 'MDS',
                'niveau' => 'master',
                'departement' => 'Informatique',
                'description' => 'Master en science des données et intelligence artificielle',
            ],
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
        }
    }
}

