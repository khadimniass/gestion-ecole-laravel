<?php

namespace App\Policies;

use App\Models\Pfe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PfePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the PFE.
     */
    public function view(User $user, Pfe $pfe)
    {
        // Admin et coordinateur peuvent tout voir
        if ($user->estAdmin() || $user->estCoordinateur()) {
            return true;
        }

        // L'encadrant peut voir ses PFE
        if ($pfe->encadrant_id === $user->id) {
            return true;
        }

        // Les étudiants peuvent voir leur PFE
        if ($pfe->etudiants->contains('id', $user->id)) {
            return true;
        }

        // Les membres du jury peuvent voir le PFE
        if ($pfe->jury->contains('membre_jury_id', $user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can update the PFE.
     */
    public function update(User $user, Pfe $pfe)
    {
        // Admin et coordinateur
        if ($user->estAdmin() || $user->estCoordinateur()) {
            return true;
        }

        // L'encadrant peut mettre à jour
        return $pfe->encadrant_id === $user->id;
    }

    /**
     * Determine if the user can upload documents.
     */
    public function uploadDocuments(User $user, Pfe $pfe)
    {
        // Les étudiants du PFE peuvent uploader
        return $pfe->etudiants->contains('id', $user->id);
    }

    /**
     * Determine if the user can terminate the PFE.
     */
    public function terminer(User $user, Pfe $pfe)
    {
        // Seulement l'encadrant peut terminer et noter
        return $pfe->encadrant_id === $user->id && $pfe->statut === 'en_cours';
    }
}
