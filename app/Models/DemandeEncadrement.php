<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeEncadrement extends Model
{
    use HasFactory;
    protected $table = 'demandes_encadrement';

    protected $fillable = [
        'etudiant_id', 'enseignant_id', 'sujet_id', 'sujet_propose',
        'description_sujet', 'motivation', 'statut', 'motif_refus',
        'date_reponse', 'annee_universitaire_id'
    ];

    protected $casts = [
        'date_reponse' => 'date',
    ];

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    public function sujet()
    {
        return $this->belongsTo(SujetPfe::class);
    }

    public function anneeUniversitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }

    public function groupe()
    {
        return $this->hasOne(GroupeEtudiants::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    // Méthodes
    public function accepter()
    {
        $this->statut = 'acceptee';
        $this->date_reponse = now();
        $this->save();

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $this->etudiant_id,
            'type' => 'demande_acceptee',
            'titre' => 'Demande d\'encadrement acceptée',
            'message' => "Votre demande d'encadrement a été acceptée par {$this->enseignant->name}",
            'data' => ['demande_id' => $this->id]
        ]);
    }

    public function refuser($motif)
    {
        $this->statut = 'refusee';
        $this->motif_refus = $motif;
        $this->date_reponse = now();
        $this->save();

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $this->etudiant_id,
            'type' => 'demande_refusee',
            'titre' => 'Demande d\'encadrement refusée',
            'message' => "Votre demande d'encadrement a été refusée. Motif : $motif",
            'data' => ['demande_id' => $this->id]
        ]);
    }
}
