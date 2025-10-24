<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandeEncadrementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->estEnseignant()) {
            $demandes = DemandeEncadrement::where('enseignant_id', $user->id)
                ->with(['etudiant', 'sujet'])
                ->latest()
                ->paginate(10);

            return view('demandes.enseignant.index', compact('demandes'));
        } else {
            $demande = DemandeEncadrement::where('etudiant_id', $user->id)
                ->where('annee_universitaire_id', AnneeUniversitaire::active()->first()->id)
                ->with(['enseignant', 'sujet'])
                ->first();

            return view('demandes.etudiant.index', compact('demande'));
        }
    }

    public function create()
    {
        // Vérifier que l'étudiant n'a pas déjà une demande cette année
        $anneeActive = AnneeUniversitaire::active()->first();

        if (!$anneeActive) {
            return back()->with('error', 'Aucune année universitaire active.');
        }

        $demandeExistante = DemandeEncadrement::where('etudiant_id', Auth::id())
            ->where('annee_universitaire_id', $anneeActive->id)
            ->first();

        if ($demandeExistante) {
            return redirect()->route('demandes.show', $demandeExistante)
                ->with('warning', 'Vous avez déjà une demande d\'encadrement cette année.');
        }

        // Vérifier que l'étudiant n'a pas déjà un PFE en cours
        if (Auth::user()->aDejaUnPfeEnCours()) {
            return back()->with('error', 'Vous avez déjà un PFE en cours.');
        }

        $enseignants = User::enseignants()
            ->actifs()
            ->where('departement', Auth::user()->filiere->departement)
            ->get();

        $sujets = SujetPfe::disponibles()
            ->parNiveau(substr(Auth::user()->niveau_etude, 0, 1) === 'L' ? 'licence' : 'master')
            ->get();

        return view('demandes.etudiant.create', compact('enseignants', 'sujets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enseignant_id' => 'required|exists:users,id',
            'type_demande' => 'required|in:sujet_existant,proposition_sujet',
            'sujet_id' => 'required_if:type_demande,sujet_existant|exists:sujets_pfe,id',
            'sujet_propose' => 'required_if:type_demande,proposition_sujet|string|max:255',
            'description_sujet' => 'required_if:type_demande,proposition_sujet|string',
            'motivation' => 'required|string|min:50',
        ]);

        $anneeActive = AnneeUniversitaire::active()->first();

        DB::beginTransaction();
        try {
            $demande = DemandeEncadrement::create([
                'etudiant_id' => Auth::id(),
                'enseignant_id' => $validated['enseignant_id'],
                'sujet_id' => $validated['sujet_id'] ?? null,
                'sujet_propose' => $validated['sujet_propose'] ?? null,
                'description_sujet' => $validated['description_sujet'] ?? null,
                'motivation' => $validated['motivation'],
                'annee_universitaire_id' => $anneeActive->id,
            ]);

            // Notifier l'enseignant
            Notification::create([
                'user_id' => $validated['enseignant_id'],
                'type' => 'nouvelle_demande_encadrement',
                'titre' => 'Nouvelle demande d\'encadrement',
                'message' => Auth::user()->name . ' vous a envoyé une demande d\'encadrement.',
                'data' => ['demande_id' => $demande->id]
            ]);

            DB::commit();

            return redirect()->route('demandes.show', $demande)
                ->with('success', 'Demande envoyée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'envoi de la demande.')
                ->withInput();
        }
    }

    public function show(DemandeEncadrement $demande)
    {
        $this->authorize('view', $demande);

        $demande->load(['etudiant', 'enseignant', 'sujet', 'groupe.membres']);

        return view('demandes.show', compact('demande'));
    }

    public function accepter(DemandeEncadrement $demande)
    {
        $this->authorize('respond', $demande);

        DB::beginTransaction();
        try {
            $demande->accepter();

            // Créer le PFE
            $pfe = Pfe::create([
                'sujet_id' => $demande->sujet_id ?? $this->creerSujetDepuisDemande($demande)->id,
                'encadrant_id' => $demande->enseignant_id,
                'annee_universitaire_id' => $demande->annee_universitaire_id,
                'date_debut' => now(),
                'date_fin_prevue' => now()->addMonths(6),
            ]);

            // Ajouter l'étudiant (et son groupe si existe)
            if ($demande->groupe) {
                $pfe->ajouterEtudiant($demande->etudiant_id, 'chef');

                foreach ($demande->groupe->membres()->wherePivot('statut', 'accepte')->get() as $membre) {
                    $pfe->ajouterEtudiant($membre->id, 'membre');
                }
            } else {
                $pfe->ajouterEtudiant($demande->etudiant_id, 'chef');
            }

            // Marquer le sujet comme affecté
            if ($demande->sujet_id) {
                $demande->sujet->update(['statut' => 'affecte']);
            }

            DB::commit();

            return redirect()->route('pfes.show', $pfe)
                ->with('success', 'Demande acceptée et PFE créé.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'acceptation de la demande : ' . $e->getMessage());
        }
    }

    public function refuser(Request $request, DemandeEncadrement $demande)
    {
        $this->authorize('respond', $demande);

        $validated = $request->validate([
            'motif_refus' => 'required|string|min:10'
        ]);

        $demande->refuser($validated['motif_refus']);

        return back()->with('success', 'Demande refusée.');
    }

    private function creerSujetDepuisDemande(DemandeEncadrement $demande)
    {
        return SujetPfe::create([
            'titre' => $demande->sujet_propose,
            'description' => $demande->description_sujet,
            'propose_par_id' => $demande->enseignant_id,
            'filiere_id' => $demande->etudiant->filiere_id, // Lier à la filière de l'étudiant
            'departement' => $demande->enseignant->departement,
            'niveau_requis' => 'tous',
            'nombre_etudiants_max' => $demande->groupe ? $demande->groupe->nombre_membres : 1,
            'annee_universitaire_id' => $demande->annee_universitaire_id,
            'statut' => 'affecte',
        ]);
    }
}
