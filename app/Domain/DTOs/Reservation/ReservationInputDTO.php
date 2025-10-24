<?php

namespace App\Domain\DTOs\Reservation;

use Carbon\Carbon;

class ReservationInputDTO
{
    public int $id_client;
    public int $id_chambre;
    public string $date_debut;
    public string $date_fin;
    // public string $tarif_template;

    public function __construct(
        int $id_client,
        int $id_chambre,
        string $date_debut,
        string $date_fin,
        // string $tarif_template
    ) {
        $this->id_client = $id_client;
        $this->id_chambre = $id_chambre;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        // $this->tarif_template = $tarif_template;
    }

    public function getNuits(): int
    {
        $debut = Carbon::parse($this->date_debut);
        $fin = Carbon::parse($this->date_fin);
        
        return $debut->diffInDays($fin);
    }

    /**
     * Valider les dates
     */
    public function estValide(): bool
    {
        $debut = \Carbon\Carbon::parse($this->date_debut);
        $fin = \Carbon\Carbon::parse($this->date_fin);
        
        return $fin->greaterThan($debut);
    }
}