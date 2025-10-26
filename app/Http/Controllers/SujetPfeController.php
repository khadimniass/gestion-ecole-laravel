<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use App\Models\Filiere;
use App\Models\Notification;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SujetPfeController extends Controller
{
    public function index(Request $request)
    {
        $query = SujetPfe::with(['proposePar', 'filiere', 'motsCles']);

        // Filtres
        if ($request->filled('departement')) {
            $query->where('departement', $request->departement);
        }

        if ($request->filled('niveau')) {
            $query->parNiveau($request->niveau);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('motsCles', function($q) use ($search) {
                        $q->where('mot', 'like', "%$search%");
                    });
            });
        }

        // Pour les étudiants, ne montrer que les sujets validés et visibles
        if (Auth::user()->estEtudiant()) {
            $query->disponibles();
        }

        $sujets = $query->latest()->paginate(15);

        return view('sujets.index', compact('sujets'));
    }

    public function indexValidation()
    {
        $this->authorize('viewValidation', SujetPfe::class);

        // Récupérer les sujets en attente de validation dans le département du coordinateur
        $sujets = SujetPfe::with(['proposePar', 'filiere', 'motsCles'])
            ->where('statut', 'propose')
            ->where('departement', Auth::user()->departement)
            ->latest()
            ->paginate(15);

        return view('sujets.validation', compact('sujets'));
    }

    public function create()
    {
        $this->authorize('create', SujetPfe::class);

        $filieres = Filiere::actives()->get();
        $anneeActive = AnneeUniversitaire::active()->first();

        if (!$anneeActive) {
            return redirect()->back()->with('error', 'Aucune année universitaire active.');
        }

        return view('sujets.create', compact('filieres', 'anneeActive'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', SujetPfe::class);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'objectifs' => 'nullable|string',
            'technologies' => 'nullable|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_requis' => 'required|in:licence,master,tous',
            'nombre_etudiants_max' => 'required|integer|min:1|max:3',
            'mots_cles' => 'nullable|array|max:4',
            'mots_cles.*' => 'string|max:50',
        ]);

        $anneeActive = AnneeUniversitaire::active()->first();

        DB::beginTransaction();
        try {
            $sujet = SujetPfe::create([
                'titre' => $validated['titre'],
                'description' => $validated['description'],
                'objectifs' => $validated['objectifs'],
                'technologies' => $validated['technologies'],
                'propose_par_id' => Auth::id(),
                'filiere_id' => $validated['filiere_id'],
                'departement' => Auth::user()->departement,
                'niveau_requis' => $validated['niveau_requis'],
                'nombre_etudiants_max' => $validated['nombre_etudiants_max'],
                'annee_universitaire_id' => $anneeActive->id,
                'statut' => Auth::user()->estCoordinateur() ? 'valide' : 'propose',
            ]);

            // Ajouter les mots-clés
            if (!empty($validated['mots_cles'])) {
                $sujet->ajouterMotsCles($validated['mots_cles']);
            }

            // Notifier les coordinateurs si besoin de validation
            if (!Auth::user()->estCoordinateur()) {
                $coordinateurs = User::where('role', 'coordinateur')
                    ->where('departement', Auth::user()->departement)
                    ->get();

                foreach ($coordinateurs as $coordinateur) {
                    Notification::create([
                        'user_id' => $coordinateur->id,
                        'type' => 'nouveau_sujet',
                        'titre' => 'Nouveau sujet à valider',
                        'message' => "Un nouveau sujet PFE a été proposé par " . Auth::user()->name,
                        'data' => ['sujet_id' => $sujet->id]
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('sujets.show', $sujet)
                ->with('success', 'Sujet créé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création du sujet.')
                ->withInput();
        }
    }

    public function show(SujetPfe $sujet)
    {
        $sujet->load(['proposePar', 'validePar', 'filiere', 'motsCles', 'pfes.etudiants']);

        return view('sujets.show', compact('sujet'));
    }

    public function edit(SujetPfe $sujet)
    {
        $this->authorize('update', $sujet);

        $filieres = Filiere::actives()->get();
        $motsCles = $sujet->motsCles->pluck('mot')->toArray();

        return view('sujets.edit', compact('sujet', 'filieres', 'motsCles'));
    }

    public function update(Request $request, SujetPfe $sujet)
    {
        $this->authorize('update', $sujet);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'objectifs' => 'nullable|string',
            'technologies' => 'nullable|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_requis' => 'required|in:licence,master,tous',
            'nombre_etudiants_max' => 'required|integer|min:1|max:3',
            'mots_cles' => 'nullable|array|max:4',
            'mots_cles.*' => 'string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $sujet->update($validated);

            // Mettre à jour les mots-clés
            if (isset($validated['mots_cles'])) {
                $sujet->ajouterMotsCles($validated['mots_cles']);
            }

            DB::commit();

            return redirect()->route('sujets.show', $sujet)
                ->with('success', 'Sujet mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour du sujet.')
                ->withInput();
        }
    }

    public function valider(SujetPfe $sujet)
    {
        $this->authorize('valider', $sujet);

        $sujet->update([
            'statut' => 'valide',
            'valide_par_id' => Auth::id(),
            'date_validation' => now(),
        ]);

        // Notifier l'enseignant
        Notification::create([
            'user_id' => $sujet->propose_par_id,
            'type' => 'sujet_valide',
            'titre' => 'Sujet validé',
            'message' => 'Votre sujet "' . $sujet->titre . '" a été validé.',
            'data' => ['sujet_id' => $sujet->id]
        ]);

        return back()->with('success', 'Sujet validé avec succès.');
    }

    public function rejeter(Request $request, SujetPfe $sujet)
    {
        $this->authorize('valider', $sujet);

        $request->validate([
            'motif' => 'required|string'
        ]);

        $sujet->update([
            'statut' => 'archive',
            'visible' => false,
        ]);

        // Notifier l'enseignant
        Notification::create([
            'user_id' => $sujet->propose_par_id,
            'type' => 'sujet_rejete',
            'titre' => 'Sujet rejeté',
            'message' => 'Votre sujet "' . $sujet->titre . '" a été rejeté. Motif: ' . $request->motif,
            'data' => ['sujet_id' => $sujet->id, 'motif' => $request->motif]
        ]);

        return back()->with('success', 'Sujet rejeté.');
    }

    public function sujetsDisponibles(Request $request)
    {
        $user = Auth::user();

        // Vérifier que c'est un étudiant
        if (!$user->estEtudiant()) {
            abort(403, 'Accès non autorisé.');
        }

        $query = SujetPfe::with(['proposePar', 'filiere', 'motsCles'])
            ->disponibles();

        // Filtrer par niveau (licence ou master) selon le niveau de l'étudiant
        $niveauEtudiant = substr($user->niveau_etude, 0, 1) === 'L' ? 'licence' : 'master';
        $query->parNiveau($niveauEtudiant);

        // Filtres optionnels
        if ($request->filled('departement')) {
            $query->where('departement', $request->departement);
        }

        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('motsCles', function($q) use ($search) {
                        $q->where('mot', 'like', "%$search%");
                    });
            });
        }

        $sujets = $query->latest()->paginate(15);
        $filieres = Filiere::actives()->get();

        return view('sujets.disponibles', compact('sujets', 'filieres'));
    }

    public function destroy(SujetPfe $sujet)
    {
        $this->authorize('delete', $sujet);

        // Vérifier qu'il n'y a pas de PFE associé
        if ($sujet->pfes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un sujet avec des PFE associés.');
        }

        $sujet->delete();

        return redirect()->route('sujets.index')
            ->with('success', 'Sujet supprimé avec succès.');
    }
}
