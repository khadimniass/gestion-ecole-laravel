<?php

namespace App\Policies;

use App\Models\Departement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any departements.
     */
    public function viewAny(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can view the departement.
     */
    public function view(User $user, Departement $departement)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can create departements.
     */
    public function create(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can update the departement.
     */
    public function update(User $user, Departement $departement)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can delete the departement.
     */
    public function delete(User $user, Departement $departement)
    {
        // Ne peut pas supprimer si des filières ou utilisateurs sont liés
        if ($departement->filieres()->exists() ||
            $departement->enseignants()->exists() ||
            $departement->coordinateurs()->exists()) {
            return false;
        }

        return $user->estAdmin();
    }
}
