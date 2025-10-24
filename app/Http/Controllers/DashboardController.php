<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use App\Models\Pfe;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $this->authorize('viewAdminDashboard', User::class);

        $anneeActive = AnneeUniversitaire::active()->first();

        $stats = [
            'total_etudiants' => User::where('role', 'etudiant')->actifs()->count(),
            'total_enseignants' => User::whereIn('role', ['enseignant', 'coordinateur'])->actifs()->count(),
            'total_sujets' => SujetPfe::where('annee_universitaire_id', $anneeActive->id)->count(),
            'sujets_valides' => SujetPfe::where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'valide')->count(),
            'pfes_en_cours' => Pfe::where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'en_cours')->count(),
            'pfes_termines' => Pfe::where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'termine')->count(),
            'demandes_en_attente' => DemandeEncadrement::where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'en_attente')->count(),
        ];

        // Statistiques par département
        $statsDepartements = User::whereIn('role', ['enseignant', 'coordinateur'])
            ->select('departement', DB::raw('count(*) as total'))
            ->groupBy('departement')
            ->get();

        // PFE récents
        $pfesRecents = Pfe::with(['sujet', 'encadrant', 'etudiants'])
            ->latest()
            ->limit(5)
            ->get();

        // Sujets en attente de validation
        $sujetsEnAttente = SujetPfe::where('statut', 'propose')
            ->with('proposePar')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'statsDepartements', 'pfesRecents', 'sujetsEnAttente'));
    }

    public function enseignant()
    {
        $anneeActive = AnneeUniversitaire::active()->first();
        $enseignant = Auth::user();

        $stats = [
            'mes_sujets' => SujetPfe::where('propose_par_id', $enseignant->id)
                ->where('annee_universitaire_id', $anneeActive->id)
                ->count(),
            'pfes_encadres' => Pfe::where('encadrant_id', $enseignant->id)
                ->where('annee_universitaire_id', $anneeActive->id)
                ->count(),
            'pfes_en_cours' => Pfe::where('encadrant_id', $enseignant->id)
                ->where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'en_cours')
                ->count(),
            'demandes_recues' => DemandeEncadrement::where('enseignant_id', $enseignant->id)
                ->where('annee_universitaire_id', $anneeActive->id)
                ->where('statut', 'en_attente')
                ->count(),
        ];

        // Mes PFE en cours
        $mesPfes = Pfe::where('encadrant_id', $enseignant->id)
            ->where('statut', 'en_cours')
            ->with(['sujet', 'etudiants'])
            ->get();

        // Demandes en attente
        $demandesEnAttente = DemandeEncadrement::where('enseignant_id', $enseignant->id)
            ->where('statut', 'en_attente')
            ->with('etudiant')
            ->latest()
            ->limit(5)
            ->get();

        // Historique des encadrements
        $historique = $enseignant->historiqueEncadrements()
            ->with('anneeUniversitaire')
            ->latest()
            ->limit(10)
            ->get();

        // Notifications non lues
        $notifications = $enseignant->notifications()
            ->nonLues()
            ->latest()
            ->limit(5)
            ->get();

        return view('enseignant.dashboard', compact('stats', 'mesPfes', 'demandesEnAttente', 'historique', 'notifications'));
    }

    public function etudiant()
    {
        $etudiant = Auth::user();
        $anneeActive = AnneeUniversitaire::active()->first();

        // Mon PFE actuel
        $monPfe = Pfe::whereHas('etudiants', function($q) use ($etudiant) {
            $q->where('etudiant_id', $etudiant->id);
        })
            ->where('annee_universitaire_id', $anneeActive->id)
            ->with(['sujet', 'encadrant', 'etudiants'])
            ->first();

        // Ma demande d'encadrement
        $maDemande = DemandeEncadrement::where('etudiant_id', $etudiant->id)
            ->where('annee_universitaire_id', $anneeActive->id)
            ->with(['enseignant', 'sujet'])
            ->first();

        // Sujets disponibles
        $sujetsDisponibles = SujetPfe::disponibles()
            ->parNiveau(substr($etudiant->niveau_etude, 0, 1) === 'L' ? 'licence' : 'master')
            ->with(['proposePar', 'motsCles'])
            ->latest()
            ->limit(10)
            ->get();

        // Notifications
        $notifications = $etudiant->notifications()
            ->latest()
            ->limit(5)
            ->get();

        // Groupe (si existe)
        $monGroupe = null;
        if ($maDemande) {
            $monGroupe = $etudiant->groupeChef ??
                $etudiant->groupesMembre()->wherePivot('statut', 'accepte')->first();
        }

        return view('etudiant.dashboard', compact('monPfe', 'maDemande', 'sujetsDisponibles', 'notifications', 'monGroupe'));
    }
}
