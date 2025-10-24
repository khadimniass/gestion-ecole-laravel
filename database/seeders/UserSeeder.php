<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les filières
        $filiereIG = Filiere::where('code', 'IG')->first();
        $filiereGL = Filiere::where('code', 'GL')->first();
        $filiereMI = Filiere::where('code', 'MI')->first();

        // Créer l'administrateur
        User::create([
            'name' => 'Administrateur Système',
            'email' => 'admin@ecole.sn',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'departement' => 'Administration',
            'active' => true,
        ]);

        // Créer les coordinateurs
        User::create([
            'name' => 'Dr. Amadou Fall',
            'email' => 'coordinateur@ecole.sn',
            'password' => Hash::make('password'),
            'role' => 'coordinateur',
            'departement' => 'Informatique',
            'active' => true,
        ]);

        // Créer les enseignants
        $enseignants = [
            [
                'name' => 'Mohamed Lamine Diakité',
                'email' => 'ml.diakite@ecole.sn',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Génie Logiciel',
                'active' => true,
            ],
            [
                'name' => 'Dr. Fatou Diallo',
                'email' => 'f.diallo@ecole.sn',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Base de données',
                'active' => true,
            ],
            [
                'name' => 'Pr. Ibrahima Sarr',
                'email' => 'i.sarr@ecole.sn',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Intelligence Artificielle',
                'active' => true,
            ],
            [
                'name' => 'Dr. Mariama Sow',
                'email' => 'enseignant@ecole.sn',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Réseaux',
                'active' => true,
            ],
        ];

        foreach ($enseignants as $enseignant) {
            User::create($enseignant);
        }

        // Créer les étudiants
        $etudiants = [
            [
                'name' => 'Aissata Elhadj BA',
                'email' => 'aissata.ba@ecole.sn',
                'matricule' => 'ETU001',
                'password' => Hash::make('ETU001'),
                'role' => 'etudiant',
                'filiere_id' => $filiereIG->id,
                'niveau_etude' => 'L3',
                'telephone' => '41184777',
                'active' => true,
            ],
            [
                'name' => 'Mamadou Diallo',
                'email' => 'mamadou.diallo@ecole.sn',
                'matricule' => 'ETU002',
                'password' => Hash::make('ETU002'),
                'role' => 'etudiant',
                'filiere_id' => $filiereIG->id,
                'niveau_etude' => 'L3',
                'telephone' => '771234567',
                'active' => true,
            ],
            [
                'name' => 'Fatima Ndiaye',
                'email' => 'etudiant@ecole.sn',
                'matricule' => 'ETU003',
                'password' => Hash::make('ETU003'),
                'role' => 'etudiant',
                'filiere_id' => $filiereGL->id,
                'niveau_etude' => 'L3',
                'telephone' => '778901234',
                'active' => true,
            ],
            [
                'name' => 'Ousmane Sy',
                'email' => 'ousmane.sy@ecole.sn',
                'matricule' => 'ETU004',
                'password' => Hash::make('ETU004'),
                'role' => 'etudiant',
                'filiere_id' => $filiereGL->id,
                'niveau_etude' => 'L3',
                'active' => true,
            ],
            [
                'name' => 'Aminata Touré',
                'email' => 'aminata.toure@ecole.sn',
                'matricule' => 'ETU005',
                'password' => Hash::make('ETU005'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI->id,
                'niveau_etude' => 'M2',
                'active' => true,
            ],
            [
                'name' => 'Cheikh Fall',
                'email' => 'cheikh.fall@ecole.sn',
                'matricule' => 'ETU006',
                'password' => Hash::make('ETU006'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI->id,
                'niveau_etude' => 'M2',
                'active' => true,
            ],
        ];

        foreach ($etudiants as $etudiant) {
            User::create($etudiant);
        }
    }
}

