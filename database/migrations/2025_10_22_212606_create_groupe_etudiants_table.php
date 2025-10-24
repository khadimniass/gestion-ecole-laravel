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
        Schema::create('groupe_etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('nom_groupe');
            $table->foreignId('chef_groupe_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('demande_encadrement_id')->nullable()->constrained('demandes_encadrement');
            $table->integer('nombre_membres');
            $table->enum('statut', ['en_formation', 'complet', 'valide'])->default('en_formation');
            $table->timestamps();
        });

        // Table pour les membres du groupe
        Schema::create('membres_groupe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupe_etudiants')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->enum('statut', ['invite', 'accepte', 'refuse'])->default('invite');
            $table->timestamps();

            $table->unique(['groupe_id', 'etudiant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('membres_groupe');
        Schema::dropIfExists('groupe_etudiants');
    }
};
