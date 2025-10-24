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
        Schema::create('demandes_encadrement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sujet_id')->nullable()->constrained('sujets_pfe')->onDelete('cascade');
            $table->string('sujet_propose')->nullable(); // Si l'étudiant propose son propre sujet
            $table->text('description_sujet')->nullable();
            $table->text('motivation');
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->date('date_reponse')->nullable();
            $table->foreignId('annee_universitaire_id')->constrained('annee_universitaires');
            $table->timestamps();

            $table->index(['statut', 'etudiant_id']);
            $table->index(['statut', 'enseignant_id']);
            $table->unique(['etudiant_id', 'annee_universitaire_id']); // Un étudiant = une demande par année
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demandes_encadrement');
    }
};
