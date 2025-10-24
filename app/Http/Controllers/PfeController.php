<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Pfe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PfeController extends Controller
{
    public function index(Request $request)
    {
        $query = Pfe::with(['sujet', 'encadrant', 'etudiants', 'anneeUniversitaire']);

        // Filtres selon le rôle
        if (Auth::user()->estEnseignant()) {
            $query->where('encadrant_id', Auth::id());
        } elseif (Auth::user()->estEtudiant()) {
            $query->whereHas('etudiants', function($q) {
                $q->where('etudiant_id', Auth::id());
            });
        }

        // Autres filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('annee')) {
            $query->where('annee_universitaire_id', $request->annee);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_pfe', 'like', "%$search%")
                    ->orWhereHas('sujet', function($q) use ($search) {
                        $q->where('titre', 'like', "%$search%");
                    })
                    ->orWhereHas('etudiants', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('matricule', 'like', "%$search%");
                    });
            });
        }

        $pfes = $query->latest()->paginate(15);

        return view('pfes.index', compact('pfes'));
    }

    public function show(Pfe $pfe)
    {
        $this->authorize('view', $pfe);

        $pfe->load(['sujet.motsCles', 'encadrant', 'etudiants', 'jury.membreJury']);

        return view('pfes.show', compact('pfe'));
    }

    public function edit(Pfe $pfe)
    {
        $this->authorize('update', $pfe);

        return view('pfes.edit', compact('pfe'));
    }

    public function update(Request $request, Pfe $pfe)
    {
        $this->authorize('update', $pfe);

        $validated = $request->validate([
            'date_fin_prevue' => 'nullable|date|after:date_debut',
            'observations' => 'nullable|string',
            'date_soutenance' => 'nullable|date',
            'salle_soutenance' => 'nullable|string',
            'heure_soutenance' => 'nullable|date_format:H:i',
        ]);

        $pfe->update($validated);

        // Si une date de soutenance est fixée, notifier les étudiants
        if (isset($validated['date_soutenance'])) {
            foreach ($pfe->etudiants as $etudiant) {
                Notification::create([
                    'user_id' => $etudiant->id,
                    'type' => 'date_soutenance',
                    'titre' => 'Date de soutenance fixée',
                    'message' => "La soutenance de votre PFE est prévue le " .
                        $pfe->date_soutenance->format('d/m/Y') .
                        ($pfe->heure_soutenance ? ' à ' . $pfe->heure_soutenance : ''),
                    'data' => ['pfe_id' => $pfe->id]
                ]);
            }
        }

        return redirect()->route('pfes.show', $pfe)
            ->with('success', 'PFE mis à jour avec succès.');
    }

    public function uploadRapport(Request $request, Pfe $pfe)
    {
        $this->authorize('uploadDocuments', $pfe);

        $request->validate([
            'rapport' => 'required|file|mimes:pdf|max:10240' // 10MB max
        ]);

        $path = $request->file('rapport')->store('rapports/' . $pfe->annee_universitaire_id, 'public');

        $pfe->update(['rapport_file' => $path]);

        // Notifier l'encadrant
        Notification::create([
            'user_id' => $pfe->encadrant_id,
            'type' => 'rapport_depose',
            'titre' => 'Rapport déposé',
            'message' => "Le rapport du PFE {$pfe->numero_pfe} a été déposé.",
            'data' => ['pfe_id' => $pfe->id]
        ]);

        return back()->with('success', 'Rapport uploadé avec succès.');
    }

    public function uploadPresentation(Request $request, Pfe $pfe)
    {
        $this->authorize('uploadDocuments', $pfe);

        $request->validate([
            'presentation' => 'required|file|mimes:pdf,pptx,ppt|max:20480' // 20MB max
        ]);

        $path = $request->file('presentation')->store('presentations/' . $pfe->annee_universitaire_id, 'public');

        $pfe->update(['presentation_file' => $path]);

        return back()->with('success', 'Présentation uploadée avec succès.');
    }

    public function downloadRapport(Pfe $pfe)
    {
        $this->authorize('view', $pfe);

        if (!$pfe->rapport_file || !Storage::disk('public')->exists($pfe->rapport_file)) {
            return back()->with('error', 'Rapport non disponible.');
        }

        return Storage::disk('public')->download($pfe->rapport_file,
            "Rapport_PFE_{$pfe->numero_pfe}.pdf");
    }

    public function downloadPresentation(Pfe $pfe)
    {
        $this->authorize('view', $pfe);

        if (!$pfe->presentation_file || !Storage::disk('public')->exists($pfe->presentation_file)) {
            return back()->with('error', 'Présentation non disponible.');
        }

        return Storage::disk('public')->download($pfe->presentation_file,
            "Presentation_PFE_{$pfe->numero_pfe}.pdf");
    }

    public function terminer(Request $request, Pfe $pfe)
    {
        $this->authorize('terminer', $pfe);

        $validated = $request->validate([
            'note_finale' => 'required|numeric|min:0|max:20',
            'notes_individuelles' => 'required|array',
            'notes_individuelles.*' => 'required|numeric|min:0|max:20',
            'appreciations' => 'nullable|array',
            'appreciations.*' => 'nullable|string'
        ]);

        // Mettre à jour les notes individuelles
        foreach ($pfe->etudiants as $etudiant) {
            $pfe->etudiants()->updateExistingPivot($etudiant->id, [
                'note_individuelle' => $validated['notes_individuelles'][$etudiant->id],
                'appreciation' => $validated['appreciations'][$etudiant->id] ?? null
            ]);
        }

        // Terminer le PFE
        $pfe->terminer($validated['note_finale']);

        // Notifier les étudiants
        foreach ($pfe->etudiants as $etudiant) {
            Notification::create([
                'user_id' => $etudiant->id,
                'type' => 'pfe_termine',
                'titre' => 'PFE terminé',
                'message' => "Votre PFE a été évalué. Note finale: {$validated['note_finale']}/20",
                'data' => ['pfe_id' => $pfe->id]
            ]);
        }

        return redirect()->route('pfes.show', $pfe)
            ->with('success', 'PFE terminé et noté avec succès.');
    }
}
