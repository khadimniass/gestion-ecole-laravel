<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportUsersRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->estAdmin() || $this->user()->estCoordinateur();
    }

    public function rules()
    {
        $rules = [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'type' => 'required|in:etudiants,enseignants',
        ];

        if ($this->input('type') === 'etudiants') {
            $rules['filiere_id'] = 'required|exists:filieres,id';
            $rules['niveau_etude'] = 'required|in:L1,L2,L3,M1,M2';
        } else {
            $rules['departement'] = 'required|string|max:100';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'file.required' => 'Le fichier est obligatoire.',
            'file.mimes' => 'Le fichier doit être au format CSV, XLSX ou XLS.',
            'file.max' => 'Le fichier ne doit pas dépasser 2MB.',
            'type.required' => 'Le type d\'import est obligatoire.',
            'filiere_id.required' => 'La filière est obligatoire pour l\'import d\'étudiants.',
            'niveau_etude.required' => 'Le niveau d\'études est obligatoire pour l\'import d\'étudiants.',
            'departement.required' => 'Le département est obligatoire pour l\'import d\'enseignants.',
        ];
    }
}
