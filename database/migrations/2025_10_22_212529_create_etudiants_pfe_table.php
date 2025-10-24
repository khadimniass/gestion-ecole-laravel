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
        Schema::create('etudiants_pfe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pfe_id')->constrained('pfes')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
            $table->enum('role_dans_groupe', ['chef', 'membre'])->default('membre');
            $table->decimal('note_individuelle', 4, 2)->nullable();
            $table->text('appreciation')->nullable();
            $table->timestamps();

            $table->unique(['pfe_id', 'etudiant_id']);
            $table->index('etudiant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etudiants_pfe');
    }
};
