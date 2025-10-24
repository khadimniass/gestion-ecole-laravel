<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotCle extends Model
{
    use HasFactory;
    protected $table = 'mots_cles';

    protected $fillable = ['mot', 'usage_count'];

    // Relations
    public function sujets()
    {
        return $this->belongsToMany(SujetPfe::class, 'sujet_mot_cle', 'mot_cle_id', 'sujet_pfe_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopePopulaires($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }
}
