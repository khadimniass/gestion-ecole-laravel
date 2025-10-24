<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueEncadrement;
use App\Models\Pfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export PFEs data
     */
    public function exportPfes(Request $request)
    {
        $this->authorize('viewAny', Pfe::class);

        $query = Pfe::with(['sujet', 'encadrant', 'etudiants', 'anneeUniversitaire']);

        // Filtres
        if ($request->filled('annee_universitaire_id')) {
            $query->where('annee_universitaire_id', $request->annee_universitaire_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $pfes = $query->get();

        // Créer le CSV
        $csvData = "Numéro PFE,Titre Sujet,Encadrant,Étudiants,Statut,Date Début,Date Fin,Note Finale,Année\n";

        foreach ($pfes as $pfe) {
            $etudiants = $pfe->etudiants->pluck('name')->implode('; ');
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $pfe->numero_pfe,
                $pfe->sujet->titre,
                $pfe->encadrant->name,
                $etudiants,
                $pfe->statut,
                $pfe->date_debut->format('d/m/Y'),
                $pfe->date_fin_prevue->format('d/m/Y'),
                $pfe->note_finale ?? 'N/A',
                $pfe->anneeUniversitaire->annee
            );
        }

        $fileName = 'export_pfes_' . date('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Export students data
     */
    public function exportEtudiants(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::where('role', 'etudiant')->with(['filiere', 'pfesEtudiant']);

        // Filtres
        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }

        if ($request->filled('niveau_etude')) {
            $query->where('niveau_etude', $request->niveau_etude);
        }

        $etudiants = $query->get();

        // Créer le CSV
        $csvData = "Matricule,Nom,Email,Téléphone,Filière,Niveau,PFE Actuel,Statut\n";

        foreach ($etudiants as $etudiant) {
            $pfeActuel = $etudiant->pfesEtudiant()
                ->where('statut', 'en_cours')
                ->first();

            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $etudiant->matricule,
                $etudiant->name,
                $etudiant->email,
                $etudiant->telephone ?? '',
                $etudiant->filiere->nom ?? '',
                $etudiant->niveau_etude,
                $pfeActuel ? $pfeActuel->numero_pfe : 'Aucun',
                $etudiant->active ? 'Actif' : 'Inactif'
            );
        }

        $fileName = 'export_etudiants_' . date('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Export encadrements data
     */
    public function exportEncadrements(Request $request)
    {
        $this->authorize('viewAny', HistoriqueEncadrement::class);

        $query = HistoriqueEncadrement::with(['enseignant', 'anneeUniversitaire']);

        // Filtres
        if ($request->filled('annee_universitaire_id')) {
            $query->where('annee_universitaire_id', $request->annee_universitaire_id);
        }

        if ($request->filled('enseignant_id')) {
            $query->where('enseignant_id', $request->enseignant_id);
        }

        $encadrements = $query->get();

        // Créer le CSV
        $csvData = "Enseignant,Titre Sujet,Étudiants,Date Début,Date Fin,Note Finale,Résultat,Année\n";

        foreach ($encadrements as $encadrement) {
            $etudiants = collect($encadrement->etudiants)
                ->pluck('nom')
                ->implode('; ');

            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $encadrement->enseignant->name,
                $encadrement->titre_sujet,
                $etudiants,
                $encadrement->date_debut->format('d/m/Y'),
                $encadrement->date_fin->format('d/m/Y'),
                $encadrement->note_finale ?? 'N/A',
                $encadrement->resultat ?? 'N/A',
                $encadrement->anneeUniversitaire->annee
            );
        }

        $fileName = 'export_encadrements_' . date('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
