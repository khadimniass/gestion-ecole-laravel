<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'matricule',
        'role',
        'telephone',
        'departement',
        'filiere_id',
        'niveau_etude',
        'specialite',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    // Relations
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // Pour les enseignants
    public function sujetsProposés()
    {
        return $this->hasMany(SujetPfe::class, 'propose_par_id');
    }

    public function sujetsValidés()
    {
        return $this->hasMany(SujetPfe::class, 'valide_par_id');
    }

    public function pfesEncadrés()
    {
        return $this->hasMany(Pfe::class, 'encadrant_id');
    }

    public function demandesEncadrementReçues()
    {
        return $this->hasMany(DemandeEncadrement::class, 'enseignant_id');
    }

    public function historiqueEncadrements()
    {
        return $this->hasMany(HistoriqueEncadrement::class, 'enseignant_id');
    }

    // Pour les étudiants
    public function pfesEtudiant()
    {
        return $this->belongsToMany(Pfe::class, 'etudiants_pfe', 'etudiant_id', 'pfe_id')
            ->withPivot('role_dans_groupe', 'note_individuelle', 'appreciation')
            ->withTimestamps();
    }

    public function demandesEncadrement()
    {
        return $this->hasMany(DemandeEncadrement::class, 'etudiant_id');
    }

    public function groupeChef()
    {
        return $this->hasOne(GroupeEtudiants::class, 'chef_groupe_id');
    }

    public function groupesMembre()
    {
        return $this->belongsToMany(GroupeEtudiants::class, 'membres_groupe', 'etudiant_id', 'groupe_id')
            ->withPivot('statut')
            ->withTimestamps();
    }

    // Pour tous
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function jurySoutenances()
    {
        return $this->hasMany(JurySoutenance::class, 'membre_jury_id');
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('active', true);
    }

    public function scopeEnseignants($query)
    {
        return $query->whereIn('role', ['enseignant', 'coordinateur']);
    }

    public function scopeEtudiants($query)
    {
        return $query->where('role', 'etudiant');
    }

    public function scopeParDepartement($query, $departement)
    {
        return $query->where('departement', $departement);
    }

    // Méthodes utilitaires
    public function estEnseignant()
    {
        return in_array($this->role, ['enseignant', 'coordinateur']);
    }

    public function estEtudiant()
    {
        return $this->role === 'etudiant';
    }

    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function estCoordinateur()
    {
        return $this->role === 'coordinateur';
    }

    public function peutEncadrer()
    {
        return $this->estEnseignant() && $this->active;
    }

    public function getNombreEncadrementsAnneeEnCours()
    {
        $anneeEnCours = AnneeUniversitaire::where('active', true)->first();
        if (!$anneeEnCours) return 0;

        return $this->pfesEncadrés()
            ->where('annee_universitaire_id', $anneeEnCours->id)
            ->where('statut', 'en_cours')
            ->count();
    }

    public function aDejaUnPfeEnCours()
    {
        $anneeEnCours = AnneeUniversitaire::where('active', true)->first();
        if (!$anneeEnCours) return false;

        return $this->pfesEtudiant()
            ->where('annee_universitaire_id', $anneeEnCours->id)
            ->whereIn('statut', ['en_cours'])
            ->exists();
    }
}
