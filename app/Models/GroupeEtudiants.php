<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeEtudiants extends Model
{
    use HasFactory;
    protected $table = 'groupe_etudiants';

    protected $fillable = [
        'nom_groupe', 'chef_groupe_id', 'demande_encadrement_id',
        'nombre_membres', 'statut'
    ];

    // Relations
    public function chefGroupe()
    {
        return $this->belongsTo(User::class, 'chef_groupe_id');
    }

    public function demandeEncadrement()
    {
        return $this->belongsTo(DemandeEncadrement::class);
    }

    public function membres()
    {
        return $this->belongsToMany(User::class, 'membres_groupe', 'groupe_id', 'etudiant_id')
            ->withPivot('statut')
            ->withTimestamps();
    }

    // Méthodes
    public function inviterMembre($etudiantId)
    {
        if ($this->membres()->count() >= 2) { // Chef + 2 membres max
            throw new \Exception("Le groupe ne peut pas avoir plus de 3 membres");
        }

        $this->membres()->attach($etudiantId, ['statut' => 'invite']);

        // Notification
        Notification::create([
            'user_id' => $etudiantId,
            'type' => 'invitation_groupe',
            'titre' => 'Invitation à rejoindre un groupe',
            'message' => "Vous avez été invité à rejoindre le groupe {$this->nom_groupe}",
            'data' => ['groupe_id' => $this->id]
        ]);
    }

    public function accepterMembre($etudiantId)
    {
        $this->membres()->updateExistingPivot($etudiantId, ['statut' => 'accepte']);

        // Vérifier si le groupe est complet
        $membresAcceptes = $this->membres()->wherePivot('statut', 'accepte')->count();
        if ($membresAcceptes + 1 === $this->nombre_membres) { // +1 pour le chef
            $this->statut = 'complet';
            $this->save();
        }
    }
}
