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
        Schema::create('typechambres', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom du type de chambre
            $table->integer('nbrLit'); // Nombre de lits
            $table->integer('maxPersonnes'); // Capacité max
            $table->text('description')->nullable(); // Optionnel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typechambres');
    }
};
