<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SujetPfe extends Model
{
    use HasFactory;

    protected $table = 'sujets_pfe';

    protected $fillable = [
        'code_sujet', 'titre', 'description', 'objectifs', 'technologies',
        'propose_par_id', 'filiere_id', 'departement', 'niveau_requis',
        'nombre_etudiants_max', 'statut', 'valide_par_id', 'date_validation',
        'annee_universitaire_id', 'visible'
    ];

    protected $casts = [
        'date_validation' => 'date',
        'visible' => 'boolean',
    ];

    // Relations
    public function proposePar()
    {
        return $this->belongsTo(User::class, 'propose_par_id');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function anneeUniversitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }

    public function motsCles()
    {
        return $this->belongsToMany(MotCle::class, 'sujet_mot_cle', 'sujet_pfe_id', 'mot_cle_id')
            ->withTimestamps();
    }

    public function pfes()
    {
        return $this->hasMany(Pfe::class, 'sujet_id');
    }

    public function demandesEncadrement()
    {
        return $this->hasMany(DemandeEncadrement::class, 'sujet_id');
    }

    // Scopes
    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('statut', 'valide')->where('visible', true);
    }

    public function scopeParDepartement($query, $departement)
    {
        return $query->where('departement', $departement);
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->whereIn('niveau_requis', [$niveau, 'tous']);
    }

    // Méthodes
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sujet) {
            // Générer un code unique
            $annee = date('Y');
            $lastCode = self::where('code_sujet', 'like', "PFE-$annee-%")
                ->orderBy('code_sujet', 'desc')
                ->first();

            if ($lastCode) {
                $number = intval(substr($lastCode->code_sujet, -4)) + 1;
            } else {
                $number = 1;
            }

            $sujet->code_sujet = sprintf("PFE-%s-%04d", $annee, $number);
        });
    }

    public function estDisponible()
    {
        return $this->statut === 'valide' && $this->visible;
    }

    public function peutEtreAffecte()
    {
        $nbAffectations = $this->pfes()->where('statut', 'en_cours')->count();
        return $nbAffectations < 1; // Un sujet ne peut être affecté qu'une fois
    }

    public function ajouterMotsCles(array $motsCles)
    {
        // Maximum 4 mots-clés
        $motsCles = array_slice($motsCles, 0, 4);

        $ids = [];
        foreach ($motsCles as $mot) {
            $motCle = MotCle::firstOrCreate(['mot' => trim(strtolower($mot))]);
            $motCle->increment('usage_count');
            $ids[] = $motCle->id;
        }

        $this->motsCles()->sync($ids);
    }
}
