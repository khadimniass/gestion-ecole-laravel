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
        Schema::create('mots_cles', function (Blueprint $table) {
            $table->id();
            $table->string('mot')->unique();
            $table->integer('usage_count')->default(0); // Pour statistiques
            $table->timestamps();
        });

        // Table pivot pour la relation many-to-many
        Schema::create('sujet_mot_cle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sujet_pfe_id')->constrained('sujets_pfe')->onDelete('cascade');
            $table->foreignId('mot_cle_id')->constrained('mots_cles')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['sujet_pfe_id', 'mot_cle_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sujet_mot_cle');
        Schema::dropIfExists('mots_cles');
    }
};
