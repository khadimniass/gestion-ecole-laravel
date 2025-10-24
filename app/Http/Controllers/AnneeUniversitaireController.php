<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use Illuminate\Http\Request;

class AnneeUniversitaireController extends Controller
{
    /**
     * Display a listing of annees universitaires
     */
    public function index()
    {
        $this->authorize('viewAny', AnneeUniversitaire::class);

        $annees = AnneeUniversitaire::withCount('sujets', 'pfes', 'demandesEncadrement')
            ->orderBy('annee', 'desc')
            ->paginate(10);

        return view('admin.annees-universitaires.index', compact('annees'));
    }

    /**
     * Show the form for creating a new annee universitaire
     */
    public function create()
    {
        $this->authorize('create', AnneeUniversitaire::class);

        return view('admin.annees-universitaires.create');
    }

    /**
     * Store a newly created annee universitaire
     */
    public function store(Request $request)
    {
        $this->authorize('create', AnneeUniversitaire::class);

        $validated = $request->validate([
            'annee' => 'required|string|regex:/^\d{4}-\d{4}$/|unique:annee_universitaires',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $annee = AnneeUniversitaire::create($validated);

        // Si c'est la première année ou demandé, l'activer
        if (AnneeUniversitaire::count() === 1 || $request->boolean('activer')) {
            $annee->setActive();
        }

        return redirect()->route('admin.annees-universitaires.index')
            ->with('success', 'Année universitaire créée avec succès.');
    }

    /**
     * Display the specified annee universitaire
     */
    public function show(AnneeUniversitaire $anneeUniversitaire)
    {
        $this->authorize('view', $anneeUniversitaire);

        $anneeUniversitaire->loadCount('sujets', 'pfes', 'demandesEncadrement');

        // Statistiques
        $stats = [
            'pfes_en_cours' => $anneeUniversitaire->pfes()->where('statut', 'en_cours')->count(),
            'pfes_termines' => $anneeUniversitaire->pfes()->where('statut', 'termine')->count(),
            'demandes_acceptees' => $anneeUniversitaire->demandesEncadrement()
                ->where('statut', 'acceptee')->count(),
            'moyenne_notes' => $anneeUniversitaire->pfes()
                ->whereNotNull('note_finale')
                ->avg('note_finale'),
        ];

        return view('admin.annees-universitaires.show', compact('anneeUniversitaire', 'stats'));
    }

    /**
     * Show the form for editing the specified annee universitaire
     */
    public function edit(AnneeUniversitaire $anneeUniversitaire)
    {
        $this->authorize('update', $anneeUniversitaire);

        return view('admin.annees-universitaires.edit', compact('anneeUniversitaire'));
    }

    /**
     * Update the specified annee universitaire
     */
    public function update(Request $request, AnneeUniversitaire $anneeUniversitaire)
    {
        $this->authorize('update', $anneeUniversitaire);

        $validated = $request->validate([
            'annee' => 'required|string|regex:/^\d{4}-\d{4}$/|unique:annee_universitaires,annee,' . $anneeUniversitaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $anneeUniversitaire->update($validated);

        // Si demandé, activer cette année
        if ($request->boolean('activer')) {
            $anneeUniversitaire->setActive();
        }

        return redirect()->route('admin.annees-universitaires.show', $anneeUniversitaire)
            ->with('success', 'Année universitaire mise à jour avec succès.');
    }

    /**
     * Remove the specified annee universitaire
     */
    public function destroy(AnneeUniversitaire $anneeUniversitaire)
    {
        $this->authorize('delete', $anneeUniversitaire);

        // Vérifier qu'il n'y a pas de données liées
        if ($anneeUniversitaire->sujets()->exists() ||
            $anneeUniversitaire->pfes()->exists() ||
            $anneeUniversitaire->demandesEncadrement()->exists()) {
            return back()->with('error', 'Impossible de supprimer une année avec des données associées.');
        }

        // Ne pas supprimer l'année active
        if ($anneeUniversitaire->active) {
            return back()->with('error', 'Impossible de supprimer l\'année active.');
        }

        $anneeUniversitaire->delete();

        return redirect()->route('admin.annees-universitaires.index')
            ->with('success', 'Année universitaire supprimée avec succès.');
    }

    /**
     * Activate a specific annee universitaire
     */
    public function activate(AnneeUniversitaire $anneeUniversitaire)
    {
        $this->authorize('update', $anneeUniversitaire);

        $anneeUniversitaire->setActive();

        return back()->with('success', 'Année universitaire activée avec succès.');
    }
}
