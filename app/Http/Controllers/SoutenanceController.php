<?php

namespace App\Http\Controllers;

use App\Models\JurySoutenance;
use App\Models\Notification;
use App\Models\Pfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoutenanceController extends Controller
{
    /**
     * Display all soutenances
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Pfe::class);

        $query = Pfe::with(['sujet', 'encadrant', 'etudiants', 'jury.membreJury'])
            ->whereNotNull('date_soutenance');

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->where('date_soutenance', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_soutenance', '<=', $request->date_fin);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'planifiee') {
                $query->where('date_soutenance', '>', now())
                    ->where('statut', 'en_cours');
            } elseif ($request->statut === 'terminee') {
                $query->where('statut', 'termine');
            }
        }

        $soutenances = $query->orderBy('date_soutenance', 'desc')
            ->paginate(20);

        return view('admin.soutenances.index', compact('soutenances'));
    }

    /**
     * Show form to edit jury for a PFE
     */
    public function editJury(Pfe $pfe)
    {
        $this->authorize('update', $pfe);

        $pfe->load(['jury.membreJury']);

        // Récupérer les enseignants disponibles pour le jury
        $enseignants = User::whereIn('role', ['enseignant', 'coordinateur'])
            ->where('active', true)
            ->where('id', '!=', $pfe->encadrant_id) // Exclure l'encadrant
            ->get();

        return view('admin.soutenances.edit-jury', compact('pfe', 'enseignants'));
    }

    /**
     * Update jury for a PFE
     */
    public function updateJury(Request $request, Pfe $pfe)
    {
        $this->authorize('update', $pfe);

        $validated = $request->validate([
            'president_id' => 'required|exists:users,id',
            'examinateurs' => 'required|array|min:1|max:3',
            'examinateurs.*' => 'exists:users,id|different:president_id',
            'rapporteur_id' => 'nullable|exists:users,id|different:president_id',
            'date_soutenance' => 'required|date|after:today',
            'heure_soutenance' => 'required|date_format:H:i',
            'salle_soutenance' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Supprimer l'ancien jury
            $pfe->jury()->delete();

            // Ajouter le président
            JurySoutenance::create([
                'pfe_id' => $pfe->id,
                'membre_jury_id' => $validated['president_id'],
                'role' => 'president',
            ]);

            // Ajouter les examinateurs
            foreach ($validated['examinateurs'] as $examinateurId) {
                JurySoutenance::create([
                    'pfe_id' => $pfe->id,
                    'membre_jury_id' => $examinateurId,
                    'role' => 'examinateur',
                ]);
            }

            // Ajouter le rapporteur si spécifié
            if (!empty($validated['rapporteur_id'])) {
                JurySoutenance::create([
                    'pfe_id' => $pfe->id,
                    'membre_jury_id' => $validated['rapporteur_id'],
                    'role' => 'rapporteur',
                ]);
            }

            // Mettre à jour les informations de soutenance
            $pfe->update([
                'date_soutenance' => $validated['date_soutenance'],
                'heure_soutenance' => $validated['heure_soutenance'],
                'salle_soutenance' => $validated['salle_soutenance'],
            ]);

            // Notifier tous les concernés
            $concernés = collect([$pfe->encadrant_id])
                ->merge($pfe->etudiants->pluck('id'))
                ->merge([$validated['president_id']])
                ->merge($validated['examinateurs'])
                ->merge([$validated['rapporteur_id']])
                ->filter()
                ->unique();

            foreach ($concernés as $userId) {
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'soutenance_planifiee',
                    'titre' => 'Soutenance planifiée',
                    'message' => "La soutenance du PFE {$pfe->numero_pfe} est prévue le " .
                        $pfe->date_soutenance->format('d/m/Y') . " à {$pfe->heure_soutenance} en {$pfe->salle_soutenance}",
                    'data' => ['pfe_id' => $pfe->id]
                ]);
            }

            DB::commit();

            return redirect()->route('admin.soutenances.index')
                ->with('success', 'Jury et date de soutenance configurés avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la configuration du jury.')
                ->withInput();
        }
    }
}
