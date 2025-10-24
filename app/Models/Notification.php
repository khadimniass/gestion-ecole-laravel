<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'type', 'titre', 'message', 'data', 'lu', 'lu_at'
    ];

    protected $casts = [
        'data' => 'array',
        'lu' => 'boolean',
        'lu_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeNonLues($query)
    {
        return $query->where('lu', false);
    }

    // Méthodes
    public function marquerCommeLue()
    {
        $this->lu = true;
        $this->lu_at = now();
        $this->save();
    }
}
