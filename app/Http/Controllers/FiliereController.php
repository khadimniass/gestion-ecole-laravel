<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    /**
     * Display a listing of filieres
     */
    public function index()
    {
        $this->authorize('viewAny', Filiere::class);

        $filieres = Filiere::withCount('etudiants', 'sujets')
            ->paginate(15);

        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Show the form for creating a new filiere
     */
    public function create()
    {
        $this->authorize('create', Filiere::class);

        return view('admin.filieres.create');
    }

    /**
     * Store a newly created filiere
     */
    public function store(Request $request)
    {
        $this->authorize('create', Filiere::class);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:filieres',
            'niveau' => 'required|in:licence,master',
            'departement' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['active'] = true;

        $filiere = Filiere::create($validated);

        return redirect()->route('admin.filieres.show', $filiere)
            ->with('success', 'Filière créée avec succès.');
    }

    /**
     * Display the specified filiere
     */
    public function show(Filiere $filiere)
    {
        $this->authorize('view', $filiere);

        $filiere->load(['etudiants' => function($q) {
            $q->limit(10);
        }, 'sujets' => function($q) {
            $q->latest()->limit(10);
        }]);

        return view('admin.filieres.show', compact('filiere'));
    }

    /**
     * Show the form for editing the specified filiere
     */
    public function edit(Filiere $filiere)
    {
        $this->authorize('update', $filiere);

        return view('admin.filieres.edit', compact('filiere'));
    }

    /**
     * Update the specified filiere
     */
    public function update(Request $request, Filiere $filiere)
    {
        $this->authorize('update', $filiere);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:filieres,code,' . $filiere->id,
            'niveau' => 'required|in:licence,master',
            'departement' => 'required|string|max:100',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $filiere->update($validated);

        return redirect()->route('admin.filieres.show', $filiere)
            ->with('success', 'Filière mise à jour avec succès.');
    }

    /**
     * Remove the specified filiere
     */
    public function destroy(Filiere $filiere)
    {
        $this->authorize('delete', $filiere);

        // Vérifier qu'il n'y a pas d'étudiants ou de sujets liés
        if ($filiere->etudiants()->exists()) {
            return back()->with('error', 'Impossible de supprimer une filière avec des étudiants.');
        }

        if ($filiere->sujets()->exists()) {
            return back()->with('error', 'Impossible de supprimer une filière avec des sujets PFE.');
        }

        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }
}
