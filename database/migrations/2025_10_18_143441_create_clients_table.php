<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Crée un bigint auto-incrémenté, clé primaire
            $table->string('nom');
            $table->string('prenom');
            $table->string('tel')->nullable(); // Téléphone peut être optionnel
            $table->string('email')->unique(); // Email unique
            $table->string('cin')->unique(); // CIN unique
            $table->timestamps(); // Crée created_at et updated_at automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
