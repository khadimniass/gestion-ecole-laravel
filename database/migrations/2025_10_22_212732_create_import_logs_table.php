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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type_import'); // etudiants, enseignants, etc.
            $table->string('fichier');
            $table->integer('nombre_lignes');
            $table->integer('nombre_succes');
            $table->integer('nombre_erreurs');
            $table->json('erreurs_details')->nullable();
            $table->foreignId('importe_par')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_logs');
    }
};
