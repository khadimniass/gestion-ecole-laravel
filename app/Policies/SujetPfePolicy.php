<?php

namespace App\Policies;

use App\Models\SujetPfe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SujetPfePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any sujets.
     */
    public function viewAny(User $user)
    {
        return true; // Tous peuvent voir la liste
    }

    /**
     * Determine if the user can view the sujet.
     */
    public function view(User $user, SujetPfe $sujet)
    {
        return true; // Tous peuvent voir un sujet
    }

    /**
     * Determine if the user can create sujets.
     */
    public function create(User $user)
    {
        return $user->estEnseignant() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can update the sujet.
     */
    public function update(User $user, SujetPfe $sujet)
    {
        // L'auteur peut modifier son sujet tant qu'il n'est pas validé
        if ($sujet->propose_par_id === $user->id && $sujet->statut === 'propose') {
            return true;
        }

        // Les coordinateurs peuvent modifier tous les sujets de leur département
        if ($user->estCoordinateur() && $sujet->departement === $user->departement) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the sujet.
     */
    public function delete(User $user, SujetPfe $sujet)
    {
        // Seulement si pas de PFE associé
        if ($sujet->pfes()->exists()) {
            return false;
        }

        // L'auteur peut supprimer son sujet non validé
        if ($sujet->propose_par_id === $user->id && $sujet->statut === 'propose') {
            return true;
        }

        // Les coordinateurs peuvent supprimer
        return $user->estCoordinateur() && $sujet->departement === $user->departement;
    }

    /**
     * Determine if the user can validate the sujet.
     */
    public function valider(User $user, SujetPfe $sujet)
    {
        return $user->estCoordinateur() &&
            $sujet->departement === $user->departement &&
            $sujet->statut === 'propose';
    }

    /**
     * Determine if the user can access the validation page.
     */
    public function viewValidation(User $user)
    {
        return $user->estCoordinateur();
    }
}
