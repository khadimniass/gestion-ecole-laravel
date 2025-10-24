<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use App\Models\HistoriqueEncadrement;
use App\Models\MotCle;
use App\Models\Pfe;
use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $resultats = collect();
        $type = $request->get('type', 'tous');

        if ($request->filled('q')) {
            $recherche = $request->q;

            if ($type === 'pfes' || $type === 'tous') {
                $pfes = Pfe::with(['sujet', 'encadrant', 'etudiants'])
                    ->where(function($query) use ($recherche) {
                        $query->where('numero_pfe', 'like', "%$recherche%")
                            ->orWhereHas('sujet', function($q) use ($recherche) {
                                $q->where('titre', 'like', "%$recherche%")
                                    ->orWhere('description', 'like', "%$recherche%");
                            })
                            ->orWhereHas('etudiants', function($q) use ($recherche) {
                                $q->where('name', 'like', "%$recherche%")
                                    ->orWhere('matricule', 'like', "%$recherche%");
                            })
                            ->orWhereHas('encadrant', function($q) use ($recherche) {
                                $q->where('name', 'like', "%$recherche%");
                            });
                    })
                    ->get();

                $resultats = $resultats->merge($pfes->map(function($pfe) {
                    return [
                        'type' => 'pfe',
                        'id' => $pfe->id,
                        'titre' => $pfe->sujet->titre,
                        'description' => "PFE N°{$pfe->numero_pfe} - {$pfe->etudiants->pluck('name')->join(', ')}",
                        'annee' => $pfe->anneeUniversitaire->annee,
                        'url' => route('pfes.show', $pfe)
                    ];
                }));
            }

            if ($type === 'sujets' || $type === 'tous') {
                $sujets = SujetPfe::with(['proposePar', 'motsCles'])
                    ->where(function($query) use ($recherche) {
                        $query->where('titre', 'like', "%$recherche%")
                            ->orWhere('description', 'like', "%$recherche%")
                            ->orWhere('code_sujet', 'like', "%$recherche%")
                            ->orWhereHas('motsCles', function($q) use ($recherche) {
                                $q->where('mot', 'like', "%$recherche%");
                            });
                    })
                    ->get();

                $resultats = $resultats->merge($sujets->map(function($sujet) {
                    return [
                        'type' => 'sujet',
                        'id' => $sujet->id,
                        'titre' => $sujet->titre,
                        'description' => "Proposé par {$sujet->proposePar->name}",
                        'annee' => $sujet->anneeUniversitaire->annee,
                        'url' => route('sujets.show', $sujet)
                    ];
                }));
            }

            if ($type === 'etudiants' || $type === 'tous') {
                $etudiants = User::where('role', 'etudiant')
                    ->where(function($query) use ($recherche) {
                        $query->where('name', 'like', "%$recherche%")
                            ->orWhere('matricule', 'like', "%$recherche%")
                            ->orWhere('email', 'like', "%$recherche%");
                    })
                    ->get();

                $resultats = $resultats->merge($etudiants->map(function($etudiant) {
                    return [
                        'type' => 'etudiant',
                        'id' => $etudiant->id,
                        'titre' => $etudiant->name,
                        'description' => "Matricule: {$etudiant->matricule} - {$etudiant->filiere->nom}",
                        'annee' => $etudiant->niveau_etude,
                        'url' => route('users.show', $etudiant)
                    ];
                }));
            }

            if ($type === 'enseignants' || $type === 'tous') {
                $enseignants = User::whereIn('role', ['enseignant', 'coordinateur'])
                    ->where(function($query) use ($recherche) {
                        $query->where('name', 'like', "%$recherche%")
                            ->orWhere('email', 'like', "%$recherche%")
                            ->orWhere('departement', 'like', "%$recherche%");
                    })
                    ->get();

                $resultats = $resultats->merge($enseignants->map(function($enseignant) {
                    return [
                        'type' => 'enseignant',
                        'id' => $enseignant->id,
                        'titre' => $enseignant->name,
                        'description' => "Département: {$enseignant->departement}",
                        'annee' => '',
                        'url' => route('users.show', $enseignant)
                    ];
                }));
            }
        }

        // Recherche par mots-clés
        if ($request->filled('mots_cles')) {
            $motsCles = explode(',', $request->mots_cles);

            $sujetsMotsCles = SujetPfe::whereHas('motsCles', function($query) use ($motsCles) {
                $query->whereIn('mot', array_map('trim', $motsCles));
            })
                ->get();

            $resultats = $resultats->merge($sujetsMotsCles->map(function($sujet) {
                return [
                    'type' => 'sujet',
                    'id' => $sujet->id,
                    'titre' => $sujet->titre,
                    'description' => "Mots-clés: " . $sujet->motsCles->pluck('mot')->join(', '),
                    'annee' => $sujet->anneeUniversitaire->annee,
                    'url' => route('sujets.show', $sujet)
                ];
            }));
        }

        // Recherche par année
        if ($request->filled('annee')) {
            $resultats = $resultats->filter(function($item) use ($request) {
                return str_contains($item['annee'], $request->annee);
            });
        }

        // Mots-clés populaires pour suggestions
        $motsClesPopulaires = MotCle::populaires(20)->get();

        return view('recherche.index', compact('resultats', 'motsClesPopulaires', 'type'));
    }

    public function historique(Request $request)
    {
        $query = HistoriqueEncadrement::with(['enseignant', 'anneeUniversitaire']);

        // Filtre par enseignant
        if ($request->filled('enseignant_id')) {
            $query->where('enseignant_id', $request->enseignant_id);
        }

        // Filtre par année
        if ($request->filled('annee_universitaire_id')) {
            $query->where('annee_universitaire_id', $request->annee_universitaire_id);
        }

        // Recherche par titre ou étudiant
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre_sujet', 'like', "%$search%")
                    ->orWhere('etudiants', 'like', "%$search%");
            });
        }

        $historiques = $query->latest()->paginate(20);

        // Liste des enseignants pour le filtre
        $enseignants = User::enseignants()->orderBy('name')->get();

        // Liste des années pour le filtre
        $annees = AnneeUniversitaire::orderBy('annee', 'desc')->get();

        return view('recherche.historique', compact('historiques', 'enseignants', 'annees'));
    }

    public function statistiques()
    {
        // Statistiques globales
        $stats = [
            'total_pfes' => Pfe::count(),
            'pfes_reussis' => Pfe::where('note_finale', '>=', 10)->count(),
            'moyenne_generale' => Pfe::whereNotNull('note_finale')->avg('note_finale'),
            'taux_reussite' => Pfe::whereNotNull('note_finale')->count() > 0 ?
                (Pfe::where('note_finale', '>=', 10)->count() / Pfe::whereNotNull('note_finale')->count()) * 100 : 0,
        ];

        // PFE par année
        $pfesParAnnee = Pfe::select('annee_universitaire_id', DB::raw('count(*) as total'))
            ->with('anneeUniversitaire')
            ->groupBy('annee_universitaire_id')
            ->get();

        // Encadrements par enseignant
        $encadrementsParEnseignant = Pfe::select('encadrant_id', DB::raw('count(*) as total'))
            ->with('encadrant')
            ->groupBy('encadrant_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Sujets les plus populaires (mots-clés)
        $motsClesPopulaires = MotCle::orderBy('usage_count', 'desc')
            ->limit(20)
            ->get();

        // Répartition par département
        $pfesParDepartement = DB::table('pfes')
            ->join('sujets_pfe', 'pfes.sujet_id', '=', 'sujets_pfe.id')
            ->select('sujets_pfe.departement', DB::raw('count(*) as total'))
            ->groupBy('sujets_pfe.departement')
            ->get();

        // Distribution des notes
        $distributionNotes = [
            '0-5' => Pfe::whereBetween('note_finale', [0, 5])->count(),
            '5-10' => Pfe::whereBetween('note_finale', [5.01, 10])->count(),
            '10-12' => Pfe::whereBetween('note_finale', [10.01, 12])->count(),
            '12-14' => Pfe::whereBetween('note_finale', [12.01, 14])->count(),
            '14-16' => Pfe::whereBetween('note_finale', [14.01, 16])->count(),
            '16-18' => Pfe::whereBetween('note_finale', [16.01, 18])->count(),
            '18-20' => Pfe::whereBetween('note_finale', [18.01, 20])->count(),
        ];

        return view('recherche.statistiques', compact(
            'stats',
            'pfesParAnnee',
            'encadrementsParEnseignant',
            'motsClesPopulaires',
            'pfesParDepartement',
            'distributionNotes'
        ));
    }
}
