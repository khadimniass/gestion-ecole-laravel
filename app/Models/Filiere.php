<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'code', 'niveau', 'departement', 'description', 'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relations
    public function etudiants()
    {
        return $this->hasMany(User::class)->where('role', 'etudiant');
    }

    public function sujets()
    {
        return $this->hasMany(SujetPfe::class);
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('active', true);
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }
}
