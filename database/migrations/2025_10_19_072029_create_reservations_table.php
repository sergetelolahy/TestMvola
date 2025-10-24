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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('id_client')->constrained('clients')->onDelete('cascade');
            $table->foreignId('id_chambre')->constrained('chambres')->onDelete('cascade');
            
            // Dates de réservation
            $table->date('date_debut');
            $table->date('date_fin');
            
            // Statut
            $table->enum('statut', [
                'confirmée', 
                'annulée', 
                'en_attente', 
                'en_cours', 
                'terminée'
            ])->default('en_attente');
            
            // Tarif
            $table->string('tarif_template', 255)->nullable();
            
            // Timestamps
            $table->timestamp('date_creation')->useCurrent();
            
            // Check-in/Check-out
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            
            // Index pour les performances
            $table->index(['id_client', 'statut']);
            $table->index(['id_chambre', 'date_debut', 'date_fin']);
            $table->index(['check_in_time', 'check_out_time']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
