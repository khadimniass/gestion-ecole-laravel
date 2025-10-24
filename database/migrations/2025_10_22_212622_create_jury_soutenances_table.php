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
        Schema::create('jury_soutenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pfe_id')->constrained('pfes')->onDelete('cascade');
            $table->foreignId('membre_jury_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['president', 'examinateur', 'rapporteur']);
            $table->decimal('note_attribuee', 4, 2)->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();

            $table->unique(['pfe_id', 'membre_jury_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jury_soutenances');
    }
};
