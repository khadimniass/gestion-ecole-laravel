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
        Schema::create('sujets_pfe', function (Blueprint $table) {
            $table->id();
            $table->string('code_sujet')->unique(); // Code unique auto-généré
            $table->string('titre');
            $table->text('description');
            $table->text('objectifs')->nullable();
            $table->text('technologies')->nullable(); // Technologies requises
            $table->foreignId('propose_par_id')->constrained('users')->onDelete('cascade'); // Enseignant qui propose
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->onDelete('set null');
            $table->string('departement');
            $table->enum('niveau_requis', ['licence', 'master', 'tous']);
            $table->integer('nombre_etudiants_max')->default(1); // 1 à 3 étudiants
            $table->enum('statut', ['propose', 'valide', 'affecte', 'archive'])->default('propose');
            $table->foreignId('valide_par_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_validation')->nullable();
            $table->foreignId('annee_universitaire_id')->constrained('annee_universitaires');
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->index(['statut', 'annee_universitaire_id']);
            $table->index('propose_par_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sujets_pfe');
    }
};
