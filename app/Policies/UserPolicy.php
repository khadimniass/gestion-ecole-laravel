<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model)
    {
        // Peut voir son propre profil
        if ($user->id === $model->id) {
            return true;
        }

        // Admin et coordinateur peuvent voir tous
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model)
    {
        // Peut modifier son propre profil
        if ($user->id === $model->id) {
            return true;
        }

        // Admin peut tout modifier
        if ($user->estAdmin()) {
            return true;
        }

        // Coordinateur peut modifier les étudiants de son département
        if ($user->estCoordinateur() && $model->estEtudiant()) {
            return $model->filiere &&
                $model->filiere->departement === $user->departement;
        }

        return false;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        // Ne peut pas se supprimer soi-même
        if ($user->id === $model->id) {
            return false;
        }

        return $user->estAdmin();
    }

    /**
     * Determine if the user can import users.
     */
    public function import(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }

    /**
     * Determine if the user can view admin dashboard.
     */
    public function viewAdminDashboard(User $user)
    {
        return $user->estAdmin() || $user->estCoordinateur();
    }
}
