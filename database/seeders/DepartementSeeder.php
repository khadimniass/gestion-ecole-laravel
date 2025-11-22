<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departements = [
            [
                'code' => 'INFO',
                'nom' => 'Informatique',
                'description' => 'Département des sciences et technologies de l\'information',
                'actif' => true,
            ],
            [
                'code' => 'MATH',
                'nom' => 'Mathématiques',
                'description' => 'Département de mathématiques et statistiques',
                'actif' => true,
            ],
            [
                'code' => 'PHYS',
                'nom' => 'Physique',
                'description' => 'Département de physique et sciences physiques',
                'actif' => true,
            ],
            [
                'code' => 'CHIM',
                'nom' => 'Chimie',
                'description' => 'Département de chimie et sciences chimiques',
                'actif' => true,
            ],
            [
                'code' => 'BIO',
                'nom' => 'Biologie',
                'description' => 'Département des sciences biologiques',
                'actif' => true,
            ],
            [
                'code' => 'GC',
                'nom' => 'Génie Civil',
                'description' => 'Département de génie civil et construction',
                'actif' => true,
            ],
            [
                'code' => 'GE',
                'nom' => 'Génie Électrique',
                'description' => 'Département de génie électrique et électronique',
                'actif' => true,
            ],
            [
                'code' => 'GM',
                'nom' => 'Génie Mécanique',
                'description' => 'Département de génie mécanique et énergétique',
                'actif' => true,
            ],
        ];

        foreach ($departements as $departement) {
            Departement::create($departement);
        }
    }
}
