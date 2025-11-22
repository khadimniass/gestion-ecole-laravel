<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Departement::class);

        $departements = Departement::withCount(['filieres', 'enseignants', 'coordinateurs'])
            ->orderBy('nom')
            ->paginate(15);

        return view('admin.departements.index', compact('departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Departement::class);

        return view('admin.departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Departement::class);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:departements,code',
            'nom' => 'required|string|max:100|unique:departements,nom',
            'description' => 'nullable|string',
            'actif' => 'boolean',
        ], [
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Ce nom existe déjà.',
        ]);

        $validated['actif'] = $request->has('actif');

        $departement = Departement::create($validated);

        return redirect()
            ->route('admin.departements.index')
            ->with('success', 'Département créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        $this->authorize('view', $departement);

        $departement->loadCount(['filieres', 'enseignants', 'coordinateurs']);

        $filieres = $departement->filieres()->withCount('etudiants')->get();
        $enseignants = $departement->enseignants()->get();
        $coordinateurs = $departement->coordinateurs()->get();

        return view('admin.departements.show', compact('departement', 'filieres', 'enseignants', 'coordinateurs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        $this->authorize('update', $departement);

        return view('admin.departements.edit', compact('departement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        $this->authorize('update', $departement);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:departements,code,' . $departement->id,
            'nom' => 'required|string|max:100|unique:departements,nom,' . $departement->id,
            'description' => 'nullable|string',
            'actif' => 'boolean',
        ], [
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique' => 'Ce nom existe déjà.',
        ]);

        $validated['actif'] = $request->has('actif');

        $departement->update($validated);

        return redirect()
            ->route('admin.departements.index')
            ->with('success', 'Département modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        $this->authorize('delete', $departement);

        // Vérifier s'il y a des filières ou des utilisateurs liés
        if ($departement->filieres()->exists() || $departement->enseignants()->exists() || $departement->coordinateurs()->exists()) {
            return redirect()
                ->route('admin.departements.index')
                ->with('error', 'Impossible de supprimer ce département car il contient des filières ou des utilisateurs.');
        }

        $departement->delete();

        return redirect()
            ->route('admin.departements.index')
            ->with('success', 'Département supprimé avec succès.');
    }

    /**
     * Toggle active status
     */
    public function toggleActif(Departement $departement)
    {
        $this->authorize('update', $departement);

        $departement->actif = !$departement->actif;
        $departement->save();

        $status = $departement->actif ? 'activé' : 'désactivé';

        return redirect()
            ->route('admin.departements.index')
            ->with('success', "Département {$status} avec succès.");
    }
}
