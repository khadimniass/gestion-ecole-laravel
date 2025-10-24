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
        Schema::create('annee_universitaires', function (Blueprint $table) {
            $table->id();
            $table->string('annee'); // ex: "2023-2024"
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('active')->default(false); // Année en cours
            $table->timestamps();

            $table->unique('annee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annee_universitaires');
    }
};
