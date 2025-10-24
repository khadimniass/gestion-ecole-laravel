<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurySoutenance extends Model
{
    use HasFactory;

    protected $table = 'jury_soutenances';

    protected $fillable = [
        'pfe_id', 'membre_jury_id', 'role', 'note_attribuee', 'commentaires'
    ];

    protected $casts = [
        'note_attribuee' => 'decimal:2',
    ];

    // Relations
    public function pfe()
    {
        return $this->belongsTo(Pfe::class);
    }

    public function membreJury()
    {
        return $this->belongsTo(User::class, 'membre_jury_id');
    }
}
