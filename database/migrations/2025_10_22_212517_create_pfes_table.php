<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pfes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pfe')->unique(); // Numéro unique du PFE
            $table->foreignId('sujet_id')->constrained('sujets_pfe')->onDelete('restrict');
            $table->foreignId('encadrant_id')->constrained('users')->onDelete('restrict'); // Enseignant encadrant
            $table->foreignId('annee_universitaire_id')->constrained('annee_universitaires');
            $table->date('date_debut');
            $table->date('date_fin_prevue');
            $table->date('date_fin_reelle')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'abandonne', 'reporte'])->default('en_cours');
            $table->decimal('note_finale', 4, 2)->nullable(); // Note sur 20
            $table->text('observations')->nullable();
            $table->date('date_soutenance')->nullable();
            $table->string('salle_soutenance')->nullable();
            $table->time('heure_soutenance')->nullable();
            $table->string('rapport_file')->nullable(); // Chemin vers le fichier PDF du rapport
            $table->string('presentation_file')->nullable(); // Chemin vers la présentation
            $table->timestamps();

            $table->index(['statut', 'annee_universitaire_id']);
            $table->index('encadrant_id');
            $table->index('date_soutenance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pfes');
    }
};
