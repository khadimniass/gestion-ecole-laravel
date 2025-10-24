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
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricule')->unique()->nullable()->after('id');
            $table->enum('role', ['admin', 'enseignant', 'etudiant', 'coordinateur'])->default('etudiant');
            $table->string('telephone')->nullable();
            $table->string('departement')->nullable();
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->onDelete('set null');
            $table->enum('niveau_etude', ['L1', 'L2', 'L3', 'M1', 'M2'])->nullable();
            $table->string('specialite')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['matricule', 'role', 'telephone', 'departement',
                'filiere_id', 'niveau_etude', 'specialite', 'active']);
        });
    }
};
