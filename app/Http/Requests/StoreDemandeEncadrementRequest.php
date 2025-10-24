<?php

namespace App\Http\Requests;

use App\Models\AnneeUniversitaire;
use App\Models\DemandeEncadrement;
use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeEncadrementRequest extends FormRequest
{
    public function authorize()
    {
        // Vérifier que l'utilisateur est un étudiant
        if (!$this->user()->estEtudiant()) {
            return false;
        }

        // Vérifier qu'il n'a pas déjà une demande cette année
        $anneeActive = AnneeUniversitaire::active()->first();
        if ($anneeActive) {
            $demandeExistante = DemandeEncadrement::where('etudiant_id', $this->user()->id)
                ->where('annee_universitaire_id', $anneeActive->id)
                ->exists();

            return !$demandeExistante;
        }

        return true;
    }

    public function rules()
    {
        $rules = [
            'enseignant_id' => 'required|exists:users,id',
            'type_demande' => 'required|in:sujet_existant,proposition_sujet',
            'motivation' => 'required|string|min:50|max:1000',
        ];

        if ($this->input('type_demande') === 'sujet_existant') {
            $rules['sujet_id'] = 'required|exists:sujets_pfe,id';
        } else {
            $rules['sujet_propose'] = 'required|string|min:10|max:255';
            $rules['description_sujet'] = 'required|string|min:50';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'enseignant_id.required' => 'Vous devez choisir un enseignant encadrant.',
            'enseignant_id.exists' => 'L\'enseignant sélectionné n\'existe pas.',
            'type_demande.required' => 'Vous devez spécifier le type de demande.',
            'motivation.required' => 'La lettre de motivation est obligatoire.',
            'motivation.min' => 'La lettre de motivation doit contenir au moins 50 caractères.',
            'motivation.max' => 'La lettre de motivation ne doit pas dépasser 1000 caractères.',
            'sujet_id.required' => 'Vous devez choisir un sujet existant.',
            'sujet_id.exists' => 'Le sujet sélectionné n\'existe pas.',
            'sujet_propose.required' => 'Le titre du sujet proposé est obligatoire.',
            'sujet_propose.min' => 'Le titre doit contenir au moins 10 caractères.',
            'description_sujet.required' => 'La description du sujet est obligatoire.',
            'description_sujet.min' => 'La description doit contenir au moins 50 caractères.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier que l'étudiant n'a pas déjà un PFE en cours
            if ($this->user()->aDejaUnPfeEnCours()) {
                $validator->errors()->add('general', 'Vous avez déjà un PFE en cours.');
            }

            // Si sujet existant, vérifier qu'il est disponible
            if ($this->input('type_demande') === 'sujet_existant' && $this->input('sujet_id')) {
                $sujet = \App\Models\SujetPfe::find($this->input('sujet_id'));
                if ($sujet && !$sujet->estDisponible()) {
                    $validator->errors()->add('sujet_id', 'Ce sujet n\'est pas disponible.');
                }

                // Vérifier le niveau requis
                if ($sujet && $sujet->niveau_requis !== 'tous') {
                    $niveauEtudiant = substr($this->user()->niveau_etude, 0, 1); // L ou M
                    $niveauRequis = $sujet->niveau_requis === 'licence' ? 'L' : 'M';

                    if (strtoupper($niveauEtudiant) !== strtoupper($niveauRequis)) {
                        $validator->errors()->add('sujet_id', 'Ce sujet n\'est pas adapté à votre niveau d\'études.');
                    }
                }
            }
        });
    }
}
