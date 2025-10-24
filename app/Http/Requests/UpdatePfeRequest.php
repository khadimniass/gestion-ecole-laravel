<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePfeRequest extends FormRequest
{
    public function authorize()
    {
        $pfe = $this->route('pfe');
        return $this->user()->can('update', $pfe);
    }

    public function rules()
    {
        return [
            'date_fin_prevue' => 'nullable|date|after:today',
            'observations' => 'nullable|string|max:1000',
            'date_soutenance' => 'nullable|date|after:today',
            'salle_soutenance' => 'nullable|string|max:50',
            'heure_soutenance' => 'nullable|date_format:H:i',
        ];
    }

    public function messages()
    {
        return [
            'date_fin_prevue.after' => 'La date de fin prévue doit être dans le futur.',
            'date_soutenance.after' => 'La date de soutenance doit être dans le futur.',
            'heure_soutenance.date_format' => 'L\'heure doit être au format HH:MM.',
        ];
    }
}
//             'description_sujet.required' => 'La description du sujet proposé est obligatoire.',
