<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerminerPfeRequest extends FormRequest
{
    public function authorize()
    {
        $pfe = $this->route('pfe');
        return $this->user()->can('terminer', $pfe);
    }

    public function rules()
    {
        $pfe = $this->route('pfe');
        $etudiantsIds = $pfe->etudiants->pluck('id')->toArray();

        $rules = [
            'note_finale' => 'required|numeric|min:0|max:20',
            'notes_individuelles' => 'required|array',
            'appreciations' => 'nullable|array',
        ];

        // Règles dynamiques pour chaque étudiant
        foreach ($etudiantsIds as $id) {
            $rules["notes_individuelles.{$id}"] = 'required|numeric|min:0|max:20';
            $rules["appreciations.{$id}"] = 'nullable|string|max:500';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'note_finale.required' => 'La note finale est obligatoire.',
            'note_finale.numeric' => 'La note doit être un nombre.',
            'note_finale.min' => 'La note ne peut pas être négative.',
            'note_finale.max' => 'La note ne peut pas dépasser 20.',
            'notes_individuelles.required' => 'Les notes individuelles sont obligatoires.',
            'notes_individuelles.*.required' => 'La note individuelle est obligatoire.',
            'notes_individuelles.*.numeric' => 'La note doit être un nombre.',
            'notes_individuelles.*.min' => 'La note ne peut pas être négative.',
            'notes_individuelles.*.max' => 'La note ne peut pas dépasser 20.',
            'appreciations.*.max' => 'L\'appréciation ne doit pas dépasser 500 caractères.',
        ];
    }
}
