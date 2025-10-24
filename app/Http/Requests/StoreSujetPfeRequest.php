<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSujetPfeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->estEnseignant() || $this->user()->estCoordinateur();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'titre' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50',
            'objectifs' => 'nullable|string|min:20',
            'technologies' => 'nullable|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_requis' => 'required|in:licence,master,tous',
            'nombre_etudiants_max' => 'required|integer|min:1|max:3',
            'mots_cles' => 'nullable|array|max:4',
            'mots_cles.*' => 'string|max:50|regex:/^[a-z0-9-]+$/',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages()
    {
        return [
            'titre.required' => 'Le titre du sujet est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 10 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 50 caractères.',
            'objectifs.min' => 'Les objectifs doivent contenir au moins 20 caractères.',
            'niveau_requis.required' => 'Le niveau requis est obligatoire.',
            'niveau_requis.in' => 'Le niveau requis doit être licence, master ou tous.',
            'nombre_etudiants_max.required' => 'Le nombre maximum d\'étudiants est obligatoire.',
            'nombre_etudiants_max.min' => 'Il faut au moins 1 étudiant.',
            'nombre_etudiants_max.max' => 'Maximum 3 étudiants par PFE.',
            'mots_cles.max' => 'Maximum 4 mots-clés autorisés.',
            'mots_cles.*.regex' => 'Les mots-clés doivent contenir uniquement des lettres minuscules, chiffres et tirets.',
        ];
    }
}
