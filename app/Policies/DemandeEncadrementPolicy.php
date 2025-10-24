<?php

namespace App\Policies;

use App\Models\DemandeEncadrement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class DemandeEncadrementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the demande.
     */
    public function view(User $user, DemandeEncadrement $demande)
    {
        // Admin et coordinateur
        if ($user->estAdmin() || $user->estCoordinateur()) {
            return true;
        }

        // L'étudiant concerné
        if ($demande->etudiant_id === $user->id) {
            return true;
        }

        // L'enseignant concerné
        if ($demande->enseignant_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can respond to the demande.
     */
    public function respond(User $user, DemandeEncadrement $demande)
    {
        // Seulement l'enseignant destinataire peut répondre
        return $demande->enseignant_id === $user->id &&
            $demande->statut === 'en_attente';
    }

    /**
     * Determine if the user can delete the demande.
     */
    public function delete(User $user, DemandeEncadrement $demande)
    {
        // L'étudiant peut annuler sa demande en attente
        return $demande->etudiant_id === $user->id &&
            $demande->statut === 'en_attente';
    }
}
