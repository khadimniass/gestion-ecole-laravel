<?php

namespace App\Policies;

use App\Models\HistoriqueEncadrement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HistoriqueEncadrementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any historiques.
     */
    public function viewAny(User $user)
    {
        // Admin, coordinateur et enseignants peuvent voir l'historique
        return $user->estAdmin() ||
            $user->estCoordinateur() ||
            $user->estEnseignant();
    }

    /**
     * Determine if the user can view the historique.
     */
    public function view(User $user, HistoriqueEncadrement $historique)
    {
        // Admin et coordinateur peuvent tout voir
        if ($user->estAdmin() || $user->estCoordinateur()) {
            return true;
        }

        // Un enseignant peut voir son propre historique
        if ($user->estEnseignant() && $historique->enseignant_id === $user->id) {
            return true;
        }

        return false;
    }
}
