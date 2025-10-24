<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_import', 'fichier', 'nombre_lignes', 'nombre_succes',
        'nombre_erreurs', 'erreurs_details', 'importe_par'
    ];

    protected $casts = [
        'erreurs_details' => 'array',
    ];

    // Relations
    public function importePar()
    {
        return $this->belongsTo(User::class, 'importe_par');
    }
}
