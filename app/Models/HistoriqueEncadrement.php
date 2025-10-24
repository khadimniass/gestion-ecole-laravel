<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueEncadrement extends Model
{
    protected $table = 'historique_encadrements';

    protected $fillable = [
        'enseignant_id', 'pfe_id', 'annee_universitaire_id',
        'titre_sujet', 'etudiants', 'date_debut', 'date_fin',
        'note_finale', 'resultat'
    ];

    protected $casts = [
        'etudiants' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'note_finale' => 'decimal:2',
    ];

    // Relations
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    public function pfe()
    {
        return $this->belongsTo(Pfe::class);
    }

    public function anneeUniversitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }
}
