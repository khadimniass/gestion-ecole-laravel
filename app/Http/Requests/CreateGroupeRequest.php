<?php

namespace App\Http\Requests;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use Illuminate\Foundation\Http\FormRequest;

class CreateGroupeRequest extends FormRequest
{
    public function authorize()
    {
        // L'utilisateur doit être étudiant et avoir une demande d'encadrement
        if (!$this->user()->estEtudiant()) {
            return false;
        }

        $anneeActive = AnneeUniversitaire::active()->first();
        if (!$anneeActive) {
            return false;
        }

        // Vérifier qu'il a une demande acceptée ou en attente
        return DemandeEncadrement::where('etudiant_id', $this->user()->id)
            ->where('annee_universitaire_id', $anneeActive->id)
            ->whereIn('statut', ['en_attente', 'acceptee'])
            ->exists();
    }

    public function rules()
    {
        return [
            'nom_groupe' => 'required|string|min:3|max:100',
            'membres' => 'required|array|min:1|max:2', // Chef + max 2 membres
            'membres.*' => 'exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'nom_groupe.required' => 'Le nom du groupe est obligatoire.',
            'nom_groupe.min' => 'Le nom du groupe doit contenir au moins 3 caractères.',
            'membres.required' => 'Vous devez ajouter au moins un membre.',
            'membres.min' => 'Le groupe doit avoir au moins 2 personnes (vous + 1 membre).',
            'membres.max' => 'Le groupe ne peut pas avoir plus de 3 personnes au total.',
            'membres.*.exists' => 'Un des membres sélectionnés n\'existe pas.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('membres')) {
                foreach ($this->input('membres') as $membreId) {
                    $membre = \App\Models\User::find($membreId);

                    // Vérifier que c'est un étudiant
                    if ($membre && !$membre->estEtudiant()) {
                        $validator->errors()->add('membres', 'Tous les membres doivent être des étudiants.');
                    }

                    // Vérifier que l'étudiant n'a pas déjà un PFE
                    if ($membre && $membre->aDejaUnPfeEnCours()) {
                        $validator->errors()->add('membres', $membre->name . ' a déjà un PFE en cours.');
                    }

                    // Vérifier que l'étudiant n'est pas déjà dans un autre groupe
                    if ($membre && $membre->groupesMembre()->wherePivot('statut', '!=', 'refuse')->exists()) {
                        $validator->errors()->add('membres', $membre->name . ' est déjà dans un groupe.');
                    }
                }
            }
        });
    }
}
