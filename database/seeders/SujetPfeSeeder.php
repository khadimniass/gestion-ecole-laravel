<?php

namespace Database\Seeders;

use App\Models\AnneeUniversitaire;
use App\Models\Filiere;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SujetPfeSeeder extends Seeder
{
    public function run()
    {
        $anneeActive = AnneeUniversitaire::where('active', true)->first();
        $enseignants = User::where('role', 'enseignant')->get();
        $coordinateur = User::where('role', 'coordinateur')->first();
        $filieres = Filiere::all();

        $sujets = [
            [
                'titre' => 'Système de Gestion et Suivi des PFE',
                'description' => 'Développer une application web pour gérer les projets de fin d\'études, incluant la proposition de sujets, les demandes d\'encadrement et le suivi.',
                'objectifs' => '1. Réaliser une interface entre l\'application développée et l\'application de gestion de la scolarité\n2. Assurer les fonctionnalités pour enseignants et étudiants\n3. Permettre la recherche historique des PFE',
                'technologies' => 'Laravel, PHP, MySQL, Vue.js',
                'propose_par_id' => $enseignants[0]->id,
                'filiere_id' => $filieres[0]->id,
                'departement' => 'Informatique',
                'niveau_requis' => 'licence',
                'nombre_etudiants_max' => 3,
                'statut' => 'valide',
                'valide_par_id' => $coordinateur->id,
                'date_validation' => now(),
                'annee_universitaire_id' => $anneeActive->id,
            ],
            [
                'titre' => 'Application Mobile de Gestion Scolaire',
                'description' => 'Créer une application mobile permettant aux étudiants et enseignants d\'accéder aux informations scolaires.',
                'objectifs' => 'Développer une app cross-platform avec authentification, consultation des notes, emplois du temps',
                'technologies' => 'React Native, Firebase, Node.js',
                'propose_par_id' => $enseignants[1]->id,
                'filiere_id' => $filieres[1]->id,
                'departement' => 'Informatique',
                'niveau_requis' => 'licence',
                'nombre_etudiants_max' => 2,
                'statut' => 'valide',
                'valide_par_id' => $coordinateur->id,
                'date_validation' => now(),
                'annee_universitaire_id' => $anneeActive->id,
            ],
            [
                'titre' => 'Système de Détection de Fraude par Machine Learning',
                'description' => 'Implémenter un système utilisant des algorithmes de machine learning pour détecter les transactions frauduleuses.',
                'objectifs' => 'Analyser les patterns de transactions, entrainer des modèles de classification, créer une API de prédiction',
                'technologies' => 'Python, TensorFlow, Scikit-learn, Flask',
                'propose_par_id' => $enseignants[2]->id,
                'filiere_id' => $filieres[3]->id,
                'departement' => 'Informatique',
                'niveau_requis' => 'master',
                'nombre_etudiants_max' => 2,
                'statut' => 'valide',
                'valide_par_id' => $coordinateur->id,
                'date_validation' => now(),
                'annee_universitaire_id' => $anneeActive->id,
            ],
            [
                'titre' => 'Plateforme E-commerce avec Recommandations Personnalisées',
                'description' => 'Développer une plateforme de commerce électronique intégrant un système de recommandations basé sur le comportement utilisateur.',
                'objectifs' => 'Créer un site e-commerce complet avec panier, paiement, et système de recommandations intelligent',
                'technologies' => 'Django, PostgreSQL, Redis, Stripe API',
                'propose_par_id' => $enseignants[0]->id,
                'filiere_id' => $filieres[0]->id,
                'departement' => 'Informatique',
                'niveau_requis' => 'licence',
                'nombre_etudiants_max' => 3,
                'statut' => 'valide',
                'valide_par_id' => $coordinateur->id,
                'date_validation' => now(),
                'annee_universitaire_id' => $anneeActive->id,
            ],
            [
                'titre' => 'Système IoT pour Smart Home',
                'description' => 'Concevoir un système domotique utilisant des capteurs IoT pour automatiser et contrôler une maison intelligente.',
                'objectifs' => 'Intégrer capteurs de température, lumière, mouvement. Créer une interface de contrôle web et mobile',
                'technologies' => 'Arduino, Raspberry Pi, MQTT, Node-RED, Angular',
                'propose_par_id' => $enseignants[3]->id,
                'filiere_id' => $filieres[2]->id,
                'departement' => 'Informatique',
                'niveau_requis' => 'tous',
                'nombre_etudiants_max' => 2,
                'statut' => 'propose',
                'annee_universitaire_id' => $anneeActive->id,
            ],
        ];

        foreach ($sujets as $sujetData) {
            $motsCles = [];

            // Extraire des mots-clés basés sur le titre et les technologies
            if (str_contains($sujetData['titre'], 'Gestion')) $motsCles[] = 'gestion';
            if (str_contains($sujetData['titre'], 'Mobile')) $motsCles[] = 'mobile';
            if (str_contains($sujetData['titre'], 'Machine Learning')) $motsCles[] = 'machine-learning';
            if (str_contains($sujetData['titre'], 'E-commerce')) $motsCles[] = 'e-commerce';
            if (str_contains($sujetData['titre'], 'IoT')) $motsCles[] = 'iot';
            if (str_contains($sujetData['technologies'], 'Laravel')) $motsCles[] = 'laravel';
            if (str_contains($sujetData['technologies'], 'Python')) $motsCles[] = 'python';

            $sujet = SujetPfe::create($sujetData);

            if (!empty($motsCles)) {
                $sujet->ajouterMotsCles(array_slice($motsCles, 0, 4));
            }
        }
    }
}
