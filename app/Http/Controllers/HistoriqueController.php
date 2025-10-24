<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoriqueEncadrement;

class HistoriqueController extends Controller
{
    /**
     * Display historique for the authenticated teacher
     */
    public function mesEncadrements(Request $request)
    {
        if (!Auth::user()->estEnseignant()) {
            abort(403, 'Accès non autorisé');
        }

        $query = Auth::user()->historiqueEncadrements();

        // Filtre par année
        if ($request->filled('annee_universitaire_id')) {
            $query->where('annee_universitaire_id', $request->annee_universitaire_id);
        }

        // Filtre par résultat
        if ($request->filled('resultat')) {
            $query->where('resultat', $request->resultat);
        }

        $historiques = $query->with('anneeUniversitaire')
            ->latest()
            ->paginate(15);

        $annees = \App\Models\AnneeUniversitaire::orderBy('annee', 'desc')->get();

        return view('enseignant.historique', compact('historiques', 'annees'));
    }
}

