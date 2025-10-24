<?php

namespace App\Http\Controllers;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use App\Models\GroupeEtudiants;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupeController extends Controller
{
    /**
     * Display the group for the authenticated student
     */
    public function index()
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est chef d'un groupe
        $groupe = $user->groupeChef;

        // Sinon, vérifier s'il est membre d'un groupe
        if (!$groupe) {
            $groupe = $user->groupesMembre()->first();
        }

        // Invitations reçues
        $invitations = $user->groupesMembre()
            ->wherePivot('statut', 'invite')
            ->get();

        return view('etudiant.groupe.index', compact('groupe', 'invitations'));
    }

    /**
     * Show the form for creating a new group
     */
    public function create()
    {
        // Vérifier que l'étudiant a une demande d'encadrement
        $anneeActive = AnneeUniversitaire::active()->first();

        $demande = DemandeEncadrement::where('etudiant_id', Auth::id())
            ->where('annee_universitaire_id', $anneeActive->id)
            ->whereIn('statut', ['en_attente', 'acceptee'])
            ->first();

        if (!$demande) {
            return redirect()->route('etudiant.demandes.create')
                ->with('error', 'Vous devez d\'abord faire une demande d\'encadrement.');
        }

        // Vérifier qu'il n'a pas déjà un groupe
        if (Auth::user()->groupeChef || Auth::user()->groupesMembre()->exists()) {
            return redirect()->route('etudiant.groupe.index')
                ->with('error', 'Vous faites déjà partie d\'un groupe.');
        }

        // Récupérer les étudiants disponibles (même filière, même niveau, sans PFE)
        $etudiantsDisponibles = User::where('role', 'etudiant')
            ->where('filiere_id', Auth::user()->filiere_id)
            ->where('niveau_etude', Auth::user()->niveau_etude)
            ->where('id', '!=', Auth::id())
            ->where('active', true)
            ->whereDoesntHave('pfesEtudiant', function($q) use ($anneeActive) {
                $q->where('annee_universitaire_id', $anneeActive->id);
            })
            ->get();

        return view('etudiant.groupe.create', compact('etudiantsDisponibles', 'demande'));
    }

    /**
     * Store a newly created group
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_groupe' => 'required|string|min:3|max:100',
            'membres' => 'nullable|array|max:2',
            'membres.*' => 'exists:users,id',
        ]);

        $anneeActive = AnneeUniversitaire::active()->first();

        // Récupérer la demande d'encadrement
        $demande = DemandeEncadrement::where('etudiant_id', Auth::id())
            ->where('annee_universitaire_id', $anneeActive->id)
            ->whereIn('statut', ['en_attente', 'acceptee'])
            ->first();

        if (!$demande) {
            return back()->with('error', 'Demande d\'encadrement introuvable.');
        }

        DB::beginTransaction();
        try {
            // Créer le groupe
            $groupe = GroupeEtudiants::create([
                'nom_groupe' => $validated['nom_groupe'],
                'chef_groupe_id' => Auth::id(),
                'demande_encadrement_id' => $demande->id,
                'nombre_membres' => count($validated['membres'] ?? []) + 1,
                'statut' => 'en_formation',
            ]);

            // Inviter les membres
            if (!empty($validated['membres'])) {
                foreach ($validated['membres'] as $membreId) {
                    $groupe->inviterMembre($membreId);
                }
            }

            DB::commit();

            return redirect()->route('etudiant.groupe.index')
                ->with('success', 'Groupe créé avec succès. Les invitations ont été envoyées.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création du groupe.')
                ->withInput();
        }
    }

    /**
     * Invite a member to the group
     */
    public function inviter(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:users,id',
        ]);

        $groupe = Auth::user()->groupeChef;

        if (!$groupe) {
            return back()->with('error', 'Vous n\'êtes pas chef de groupe.');
        }

        try {
            $groupe->inviterMembre($validated['etudiant_id']);

            return back()->with('success', 'Invitation envoyée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Accept a group invitation
     */
    public function accepterInvitation($groupeId)
    {
        $groupe = GroupeEtudiants::findOrFail($groupeId);

        // Vérifier que l'utilisateur est invité
        if (!$groupe->membres()->where('etudiant_id', Auth::id())
            ->wherePivot('statut', 'invite')
            ->exists()) {
            return back()->with('error', 'Invitation invalide.');
        }

        $groupe->accepterMembre(Auth::id());

        // Notifier le chef de groupe
        Notification::create([
            'user_id' => $groupe->chef_groupe_id,
            'type' => 'invitation_acceptee',
            'titre' => 'Invitation acceptée',
            'message' => Auth::user()->name . ' a accepté de rejoindre le groupe.',
            'data' => ['groupe_id' => $groupe->id]
        ]);

        return redirect()->route('etudiant.groupe.index')
            ->with('success', 'Vous avez rejoint le groupe avec succès.');
    }

    /**
     * Refuse a group invitation
     */
    public function refuserInvitation($groupeId)
    {
        $groupe = GroupeEtudiants::findOrFail($groupeId);

        // Vérifier que l'utilisateur est invité
        if (!$groupe->membres()->where('etudiant_id', Auth::id())
            ->wherePivot('statut', 'invite')
            ->exists()) {
            return back()->with('error', 'Invitation invalide.');
        }

        $groupe->membres()->updateExistingPivot(Auth::id(), ['statut' => 'refuse']);

        // Notifier le chef de groupe
        Notification::create([
            'user_id' => $groupe->chef_groupe_id,
            'type' => 'invitation_refusee',
            'titre' => 'Invitation refusée',
            'message' => Auth::user()->name . ' a refusé de rejoindre le groupe.',
            'data' => ['groupe_id' => $groupe->id]
        ]);

        return back()->with('success', 'Invitation refusée.');
    }

    /**
     * Remove a member from the group
     */
    public function retirerMembre($membreId)
    {
        $groupe = Auth::user()->groupeChef;

        if (!$groupe) {
            return back()->with('error', 'Vous n\'êtes pas chef de groupe.');
        }

        $groupe->membres()->detach($membreId);

        // Notifier le membre retiré
        Notification::create([
            'user_id' => $membreId,
            'type' => 'retire_du_groupe',
            'titre' => 'Retiré du groupe',
            'message' => 'Vous avez été retiré du groupe ' . $groupe->nom_groupe,
            'data' => ['groupe_id' => $groupe->id]
        ]);

        return back()->with('success', 'Membre retiré du groupe.');
    }
}
