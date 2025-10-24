<?php

namespace Database\Seeders;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use App\Models\GroupeEtudiants;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemandeEncadrementSeeder extends Seeder
{
    public function run()
    {
        $anneeActive = AnneeUniversitaire::where('active', true)->first();
        $etudiants = User::where('role', 'etudiant')->get();
        $enseignants = User::where('role', 'enseignant')->get();
        $sujets = SujetPfe::where('statut', 'valide')->get();

        // Demande acceptée avec groupe
        $demande1 = DemandeEncadrement::create([
            'etudiant_id' => $etudiants[0]->id, // Aissata
            'enseignant_id' => $enseignants[0]->id, // Mohamed Lamine
            'sujet_id' => $sujets[0]->id, // Système de Gestion PFE
            'motivation' => 'Je suis passionnée par le développement web et ce projet correspond parfaitement à mes compétences en Laravel. J\'ai déjà travaillé sur des projets similaires durant mes stages.',
            'statut' => 'acceptee',
            'date_reponse' => now()->subDays(5),
            'annee_universitaire_id' => $anneeActive->id,
        ]);

        // Créer un groupe pour cette demande
        $groupe = GroupeEtudiants::create([
            'nom_groupe' => 'Groupe PFE Gestion',
            'chef_groupe_id' => $etudiants[0]->id,
            'demande_encadrement_id' => $demande1->id,
            'nombre_membres' => 2,
            'statut' => 'complet',
        ]);

        // Ajouter un membre au groupe
        $groupe->membres()->attach($etudiants[1]->id, ['statut' => 'accepte']);

        // Demande en attente
        DemandeEncadrement::create([
            'etudiant_id' => $etudiants[2]->id, // Fatima
            'enseignant_id' => $enseignants[1]->id,
            'sujet_id' => $sujets[1]->id, // App Mobile
            'motivation' => 'Le développement mobile m\'intéresse beaucoup et j\'aimerais approfondir mes connaissances en React Native.',
            'statut' => 'en_attente',
            'annee_universitaire_id' => $anneeActive->id,
        ]);

        // Demande refusée
        DemandeEncadrement::create([
            'etudiant_id' => $etudiants[3]->id, // Ousmane
            'enseignant_id' => $enseignants[2]->id,
            'sujet_id' => $sujets[2]->id, // ML
            'motivation' => 'Je souhaite travailler sur ce projet de machine learning.',
            'statut' => 'refusee',
            'motif_refus' => 'Le sujet nécessite des prérequis en Master que vous n\'avez pas encore.',
            'date_reponse' => now()->subDays(2),
            'annee_universitaire_id' => $anneeActive->id,
        ]);

        // Demande avec proposition de sujet
        DemandeEncadrement::create([
            'etudiant_id' => $etudiants[4]->id, // Aminata
            'enseignant_id' => $enseignants[2]->id,
            'sujet_propose' => 'Analyse de Sentiments sur les Réseaux Sociaux',
            'description_sujet' => 'Développer un système d\'analyse de sentiments pour extraire et analyser les opinions sur Twitter concernant les produits locaux.',
            'motivation' => 'Ce projet combine mes compétences en NLP et mon intérêt pour l\'analyse des données sociales. J\'ai déjà une expérience avec les APIs Twitter.',
            'statut' => 'en_attente',
            'annee_universitaire_id' => $anneeActive->id,
        ]);
    }
}
