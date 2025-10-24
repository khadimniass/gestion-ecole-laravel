<?php

namespace App\Policies;

use App\Models\Filiere;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilierePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any filieres.
     */
    public function viewAny(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can view the filiere.
     */
    public function view(User $user, Filiere $filiere)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can create filieres.
     */
    public function create(User $user)
    {
        return $user->estAdmin();
    }

    /**
     * Determine if the user can update the filiere.
     */
    public function update(User $user, Filiere $filiere)
    {
        return $user->estAdmin();
    }

    /**
     * Determine if the user can delete the filiere.
     */
    public function delete(User $user, Filiere $filiere)
    {
        // Ne peut pas supprimer si des étudiants ou sujets sont liés
        if ($filiere->etudiants()->exists() || $filiere->sujets()->exists()) {
            return false;
        }

        return $user->estAdmin();
    }
}
