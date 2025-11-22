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
        $filiereInfo = Filiere::where('code', 'INFO')->first();
        $filiereIG = Filiere::where('code', 'IG')->first();
        $filiereGL = Filiere::where('code', 'GL')->first();
        $filiereMI = Filiere::where('code', 'MI')->first();

        // Créer l'administrateur
        User::create([
            'name' => 'Administrateur Système',
            'email' => 'admin@gestion-pfe.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'departement' => 'Administration',
            'active' => true,
        ]);

        // Créer les coordinateurs
        User::create([
            'name' => 'Dr. Amadou Fall',
            'email' => 'coord.info@gestion-pfe.test',
            'password' => Hash::make('password'),
            'role' => 'coordinateur',
            'departement' => 'Informatique',
            'active' => true,
        ]);

        User::create([
            'name' => 'Dr. Khady Sow',
            'email' => 'coord.math@gestion-pfe.test',
            'password' => Hash::make('password'),
            'role' => 'coordinateur',
            'departement' => 'Mathématiques',
            'active' => true,
        ]);

        // Créer les enseignants
        $enseignants = [
            // Enseignants Informatique
            [
                'name' => 'Prof. Jean Dupont',
                'email' => 'prof.dupont@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Intelligence Artificielle',
                'telephone' => '771234567',
                'active' => true,
            ],
            [
                'name' => 'Prof. Marie Martin',
                'email' => 'prof.martin@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Développement Web',
                'telephone' => '775551234',
                'active' => true,
            ],
            [
                'name' => 'Dr. Mamadou Diop',
                'email' => 'prof.diop@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Réseaux et Sécurité',
                'telephone' => '776662345',
                'active' => true,
            ],
            [
                'name' => 'Dr. Fatou Diallo',
                'email' => 'prof.diallo@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Base de Données',
                'telephone' => '777773456',
                'active' => true,
            ],
            [
                'name' => 'Pr. Ibrahima Sarr',
                'email' => 'prof.sarr@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Génie Logiciel',
                'telephone' => '778884567',
                'active' => true,
            ],
            [
                'name' => 'Dr. Aissatou Sow',
                'email' => 'prof.sow@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Informatique',
                'specialite' => 'Systèmes Embarqués',
                'telephone' => '779995678',
                'active' => true,
            ],
            // Enseignants Mathématiques
            [
                'name' => 'Prof. Omar Ndiaye',
                'email' => 'prof.ndiaye@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Mathématiques',
                'specialite' => 'Analyse Numérique',
                'telephone' => '771116789',
                'active' => true,
            ],
            [
                'name' => 'Dr. Khady Kane',
                'email' => 'prof.kane@gestion-pfe.test',
                'password' => Hash::make('password'),
                'role' => 'enseignant',
                'departement' => 'Mathématiques',
                'specialite' => 'Algèbre',
                'telephone' => '772227890',
                'active' => true,
            ],
        ];

        foreach ($enseignants as $enseignant) {
            User::create($enseignant);
        }

        // Créer les étudiants
        // Utilisons $filiereInfo si elle existe, sinon IG
        $filiereInfoId = $filiereInfo ? $filiereInfo->id : ($filiereIG ? $filiereIG->id : null);

        $etudiants = [
            // Étudiants principaux pour tests
            [
                'name' => 'Khadim Niass',
                'email' => 'khadim.niass@etudiant.test',
                'matricule' => 'C98363',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '771234567',
                'active' => true,
            ],
            [
                'name' => 'Aissatou Ba',
                'email' => 'aissatou.ba@etudiant.test',
                'matricule' => 'C73652',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '775551234',
                'active' => true,
            ],
            [
                'name' => 'Moussa Diallo',
                'email' => 'moussa.diallo@etudiant.test',
                'matricule' => 'C89254',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '776662345',
                'active' => true,
            ],
            // Plus d'étudiants Licence
            [
                'name' => 'Fatou Sow',
                'email' => 'fatou.sow@etudiant.test',
                'matricule' => 'C53975',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '777773456',
                'active' => true,
            ],
            [
                'name' => 'Amadou Fall',
                'email' => 'amadou.fall@etudiant.test',
                'matricule' => 'C42186',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '778884567',
                'active' => true,
            ],
            [
                'name' => 'Mariama Diop',
                'email' => 'mariama.diop@etudiant.test',
                'matricule' => 'C65297',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '779995678',
                'active' => true,
            ],
            [
                'name' => 'Ousmane Ndiaye',
                'email' => 'ousmane.ndiaye@etudiant.test',
                'matricule' => 'C78394',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '771116789',
                'active' => true,
            ],
            [
                'name' => 'Awa Sy',
                'email' => 'awa.sy@etudiant.test',
                'matricule' => 'C91405',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '772227890',
                'active' => true,
            ],
            [
                'name' => 'Ibrahima Sarr',
                'email' => 'ibrahima.sarr@etudiant.test',
                'matricule' => 'C24518',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '773338901',
                'active' => true,
            ],
            [
                'name' => 'Bineta Kane',
                'email' => 'bineta.kane@etudiant.test',
                'matricule' => 'L2023009',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '774449012',
                'active' => true,
            ],
            [
                'name' => 'Cheikh Toure',
                'email' => 'cheikh.toure@etudiant.test',
                'matricule' => 'L2023010',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereInfoId,
                'niveau_etude' => 'licence',
                'telephone' => '775550123',
                'active' => true,
            ],
            // Étudiants Master
            [
                'name' => 'Ndèye Gueye',
                'email' => 'ndeye.gueye@etudiant.test',
                'matricule' => 'C36729',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '776661234',
                'active' => true,
            ],
            [
                'name' => 'Abdoulaye Seck',
                'email' => 'abdoulaye.seck@etudiant.test',
                'matricule' => 'C58130',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '777772345',
                'active' => true,
            ],
            [
                'name' => 'Khady Diouf',
                'email' => 'khady.diouf@etudiant.test',
                'matricule' => 'M2023004',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '778883456',
                'active' => true,
            ],
            [
                'name' => 'Moustapha Cisse',
                'email' => 'moustapha.cisse@etudiant.test',
                'matricule' => 'M2023005',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '779994567',
                'active' => true,
            ],
            [
                'name' => 'Coumba Faye',
                'email' => 'coumba.faye@etudiant.test',
                'matricule' => 'M2023006',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereMI ? $filiereMI->id : $filiereInfoId,
                'niveau_etude' => 'master',
                'telephone' => '771115678',
                'active' => true,
            ],
        ];

        foreach ($etudiants as $etudiant) {
            User::create($etudiant);
        }
    }
}

