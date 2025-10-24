<?php

namespace App\Policies;

use App\Models\AnneeUniversitaire;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnneeUniversitairePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any annees.
     */
    public function viewAny(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can view the annee.
     */
    public function view(User $user, AnneeUniversitaire $annee)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can create annees.
     */
    public function create(User $user)
    {
        return $user->estAdmin();
    }

    /**
     * Determine if the user can update the annee.
     */
    public function update(User $user, AnneeUniversitaire $annee)
    {
        return $user->estAdmin();
    }

    /**
     * Determine if the user can delete the annee.
     */
    public function delete(User $user, AnneeUniversitaire $annee)
    {
        // Ne peut pas supprimer l'année active
        if ($annee->active) {
            return false;
        }

        // Ne peut pas supprimer si des données sont liées
        if ($annee->sujets()->exists() ||
            $annee->pfes()->exists() ||
            $annee->demandesEncadrement()->exists()) {
            return false;
        }

        return $user->estAdmin();
    }
}
