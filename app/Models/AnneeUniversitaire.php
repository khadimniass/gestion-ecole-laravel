<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeUniversitaire extends Model
{
    use HasFactory;
    protected $fillable = ['annee', 'date_debut', 'date_fin', 'active'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean',
    ];

    // Relations
    public function sujets()
    {
        return $this->hasMany(SujetPfe::class);
    }

    public function pfes()
    {
        return $this->hasMany(Pfe::class);
    }

    public function demandesEncadrement()
    {
        return $this->hasMany(DemandeEncadrement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Méthodes
    public static function anneeCourante()
    {
        return self::where('active', true)->first();
    }

    public function setActive()
    {
        // Désactiver toutes les autres années
        self::where('active', true)->update(['active' => false]);

        // Activer cette année
        $this->active = true;
        $this->save();
    }
}
