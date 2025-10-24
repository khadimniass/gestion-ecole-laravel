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
        // Charger l'étudiant à inviter
        $etudiant = User::find($etudiantId);

        if (!$etudiant) {
            throw new \Exception("Étudiant introuvable");
        }

        // Vérifier que c'est bien un étudiant
        if (!$etudiant->estEtudiant()) {
            throw new \Exception("Seuls les étudiants peuvent être invités dans un groupe");
        }

        // Vérifier le nombre maximum de membres
        if ($this->membres()->count() >= 2) { // Chef + 2 membres max
            throw new \Exception("Le groupe ne peut pas avoir plus de 3 membres");
        }

        // Vérifier que l'étudiant n'est pas déjà dans le groupe
        if ($this->membres()->where('users.id', $etudiantId)->exists()) {
            throw new \Exception("Cet étudiant fait déjà partie du groupe");
        }

        // Vérifier que le chef et l'étudiant invité sont de la même filière
        $chef = $this->chefGroupe;
        if ($etudiant->filiere_id != $chef->filiere_id) {
            throw new \Exception(
                "Tous les membres d'un groupe doivent être de la même filière. " .
                "Filière du groupe : " . $chef->filiere->nom
            );
        }

        // Vérifier que tous les membres existants sont de la même filière
        $membresExistants = $this->membres()->wherePivot('statut', '!=', 'refuse')->get();
        foreach ($membresExistants as $membre) {
            if ($membre->filiere_id != $etudiant->filiere_id) {
                throw new \Exception(
                    "Tous les membres d'un groupe doivent être de la même filière. " .
                    "Filière requise : " . $membre->filiere->nom
                );
            }
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
