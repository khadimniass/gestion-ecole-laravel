<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'description',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Les filières du département
     */
    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'departement', 'nom');
    }

    /**
     * Les enseignants du département
     */
    public function enseignants()
    {
        return $this->hasMany(User::class, 'departement', 'nom')
                    ->where('role', 'enseignant');
    }

    /**
     * Les coordinateurs du département
     */
    public function coordinateurs()
    {
        return $this->hasMany(User::class, 'departement', 'nom')
                    ->where('role', 'coordinateur');
    }

    /**
     * Vérifie si le département est actif
     */
    public function estActif()
    {
        return $this->actif;
    }

    /**
     * Scope pour les départements actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
