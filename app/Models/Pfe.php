<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pfe extends Model
{
    use HasFactory;

    protected $table = 'pfes';

    protected $fillable = [
        'numero_pfe', 'sujet_id', 'encadrant_id', 'annee_universitaire_id',
        'date_debut', 'date_fin_prevue', 'date_fin_reelle', 'statut',
        'note_finale', 'observations', 'date_soutenance', 'salle_soutenance',
        'heure_soutenance', 'rapport_file', 'presentation_file'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'date_soutenance' => 'date',
        'note_finale' => 'decimal:2',
    ];

    // Relations
    public function sujet()
    {
        return $this->belongsTo(SujetPfe::class);
    }

    public function encadrant()
    {
        return $this->belongsTo(User::class, 'encadrant_id');
    }

    public function anneeUniversitaire()
    {
        return $this->belongsTo(AnneeUniversitaire::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(User::class, 'etudiants_pfe', 'pfe_id', 'etudiant_id')
            ->withPivot('role_dans_groupe', 'note_individuelle', 'appreciation')
            ->withTimestamps();
    }

    public function jury()
    {
        return $this->hasMany(JurySoutenance::class);
    }

    public function historique()
    {
        return $this->hasOne(HistoriqueEncadrement::class);
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeParEncadrant($query, $encadrantId)
    {
        return $query->where('encadrant_id', $encadrantId);
    }

    // Méthodes
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pfe) {
            // Générer un numéro unique
            $annee = AnneeUniversitaire::find($pfe->annee_universitaire_id);
            $anneeStr = str_replace('-', '', substr($annee->annee, 0, 4));

            $lastPfe = self::where('numero_pfe', 'like', "PFE$anneeStr%")
                ->orderBy('numero_pfe', 'desc')
                ->first();

            if ($lastPfe) {
                $number = intval(substr($lastPfe->numero_pfe, -4)) + 1;
            } else {
                $number = 1;
            }

            $pfe->numero_pfe = sprintf("PFE%s%04d", $anneeStr, $number);
        });

        // Créer l'historique quand le PFE est terminé
        static::updated(function ($pfe) {
            if ($pfe->statut === 'termine' && $pfe->isDirty('statut')) {
                $pfe->creerHistorique();
            }
        });
    }

    public function ajouterEtudiant($etudiantId, $role = 'membre')
    {
        // Charger l'étudiant
        $etudiant = User::find($etudiantId);

        if (!$etudiant) {
            throw new \Exception("Étudiant introuvable");
        }

        // Vérifier que c'est bien un étudiant
        if (!$etudiant->estEtudiant()) {
            throw new \Exception("L'utilisateur doit être un étudiant");
        }

        // Vérifier le nombre maximum d'étudiants
        if ($this->etudiants()->count() >= $this->sujet->nombre_etudiants_max) {
            throw new \Exception("Nombre maximum d'étudiants atteint pour ce PFE");
        }

        // Vérifier que l'étudiant n'est pas déjà dans le PFE
        if ($this->etudiants()->where('users.id', $etudiantId)->exists()) {
            throw new \Exception("Cet étudiant fait déjà partie de ce PFE");
        }

        // Validation de la filière : tous les étudiants doivent être de la même filière
        $etudiantsExistants = $this->etudiants;

        // Si le sujet a une filière spécifiée, vérifier que l'étudiant est de cette filière
        if ($this->sujet->filiere_id) {
            if ($etudiant->filiere_id != $this->sujet->filiere_id) {
                throw new \Exception(
                    "L'étudiant doit être de la même filière que le sujet (" .
                    $this->sujet->filiere->nom . ")"
                );
            }
        }

        // Si des étudiants sont déjà dans le PFE, vérifier qu'ils sont de la même filière
        if ($etudiantsExistants->count() > 0) {
            $filiereExistante = $etudiantsExistants->first()->filiere_id;

            if ($etudiant->filiere_id != $filiereExistante) {
                throw new \Exception(
                    "Tous les étudiants d'un PFE doivent être de la même filière. " .
                    "Filière requise : " . $etudiantsExistants->first()->filiere->nom
                );
            }
        }

        $this->etudiants()->attach($etudiantId, ['role_dans_groupe' => $role]);
    }

    public function terminer($noteFinal = null)
    {
        $this->statut = 'termine';
        $this->date_fin_reelle = now();
        if ($noteFinal) {
            $this->note_finale = $noteFinal;
        }
        $this->save();
    }

    public function creerHistorique()
    {
        $etudiants = $this->etudiants->map(function ($etudiant) {
            return [
                'nom' => $etudiant->name,
                'matricule' => $etudiant->matricule,
                'note' => $etudiant->pivot->note_individuelle
            ];
        });

        HistoriqueEncadrement::create([
            'enseignant_id' => $this->encadrant_id,
            'pfe_id' => $this->id,
            'annee_universitaire_id' => $this->annee_universitaire_id,
            'titre_sujet' => $this->sujet->titre,
            'etudiants' => $etudiants,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin_reelle ?? $this->date_fin_prevue,
            'note_finale' => $this->note_finale,
            'resultat' => $this->note_finale >= 10 ? 'reussi' : 'echoue'
        ]);
    }
}
