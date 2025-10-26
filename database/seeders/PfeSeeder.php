<?php

namespace Database\Seeders;

use App\Models\AnneeUniversitaire;
use App\Models\Pfe;
use App\Models\SujetPfe;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PfeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $annee = AnneeUniversitaire::where('active', true)->first();
        $enseignants = User::where('role', 'enseignant')->get();
        $etudiants = User::where('role', 'etudiant')->get();
        $sujets = SujetPfe::where('statut', 'valide')->get();

        if (!$annee || $enseignants->isEmpty() || $etudiants->isEmpty()) {
            $this->command->warn('Pas assez de données pour créer des PFEs. Veuillez d\'abord exécuter les autres seeders.');
            return;
        }

        // Créer 8 PFEs avec différents statuts
        $statuts = ['en_cours', 'en_cours', 'en_cours', 'termine', 'termine', 'en_attente_soutenance', 'en_attente_soutenance', 'propose'];

        foreach ($statuts as $index => $statut) {
            $sujet = $sujets->random();
            $encadrant = $enseignants->random();

            // Dates selon le statut
            $dateDebut = Carbon::now()->subMonths(rand(2, 10));
            $dateFinPrevue = (clone $dateDebut)->addMonths(6);
            $dateFinReelle = null;
            $dateSoutenance = null;
            $noteFinal = null;

            if ($statut === 'termine') {
                $dateFinReelle = (clone $dateDebut)->addMonths(rand(5, 7));
                $dateSoutenance = (clone $dateFinReelle)->addDays(rand(7, 14));
                $noteFinal = rand(12, 20);
            } elseif ($statut === 'en_attente_soutenance') {
                $dateFinReelle = (clone $dateDebut)->addMonths(6);
                $dateSoutenance = Carbon::now()->addDays(rand(7, 30));
            }

            $pfe = Pfe::create([
                'sujet_id' => $sujet->id,
                'encadrant_id' => $encadrant->id,
                'annee_universitaire_id' => $annee->id,
                'date_debut' => $dateDebut,
                'date_fin_prevue' => $dateFinPrevue,
                'date_fin_reelle' => $dateFinReelle,
                'statut' => $statut,
                'note_finale' => $noteFinal,
                'date_soutenance' => $dateSoutenance,
                'salle_soutenance' => $dateSoutenance ? 'Salle ' . chr(65 + rand(0, 5)) . rand(100, 400) : null,
                'heure_soutenance' => $dateSoutenance ? sprintf('%02d:00', rand(8, 16)) : null,
                'observations' => $statut === 'termine' ? 'PFE terminé avec succès' : null,
            ]);

            // Attacher 1 à 2 étudiants (avec même filière que le sujet)
            $nombreEtudiants = rand(1, 2);
            $etudiantsFiliere = $etudiants->where('filiere_id', $sujet->filiere_id);
            
            if ($etudiantsFiliere->count() >= $nombreEtudiants) {
                $etudiantsSelectionnes = $etudiantsFiliere->random(min($nombreEtudiants, $etudiantsFiliere->count()));

                foreach ($etudiantsSelectionnes as $etudiantIndex => $etudiant) {
                    $pfe->etudiants()->attach($etudiant->id, [
                        'role_dans_groupe' => $etudiantIndex === 0 ? 'chef' : 'membre',
                        'note_individuelle' => $statut === 'termine' ? rand(12, 20) : null,
                        'appreciation' => $statut === 'termine' ? $this->getAppreciation() : null,
                    ]);
                }
            }

            $this->command->info("PFE créé: {$pfe->numero_pfe} - Statut: {$statut}");
        }

        $this->command->info('PfeSeeder terminé avec succès!');
    }

    private function getAppreciation()
    {
        $appreciations = [
            'Excellent travail, très bon niveau technique',
            'Bon travail dans l\'ensemble',
            'Travail satisfaisant, quelques améliorations possibles',
            'Très bonne maîtrise du sujet',
            'Présentation claire et complète',
        ];

        return $appreciations[array_rand($appreciations)];
    }
}
