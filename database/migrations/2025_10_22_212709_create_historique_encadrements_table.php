<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_encadrements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pfe_id')->constrained('pfes')->onDelete('cascade');
            $table->foreignId('annee_universitaire_id')->constrained('annee_universitaires');
            $table->string('titre_sujet');
            $table->json('etudiants'); // Liste des étudiants encadrés
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('note_finale', 4, 2)->nullable();
            $table->enum('resultat', ['reussi', 'echoue', 'abandonne'])->nullable();
            $table->timestamps();

            // ✅ Nom court pour l'index
            $table->index(['enseignant_id', 'annee_universitaire_id'], 'hist_encad_index');

            // (Cette ligne est optionnelle car déjà incluse dans la précédente)
            // $table->index('annee_universitaire_id', 'annee_univ_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_encadrements');
    }
};
