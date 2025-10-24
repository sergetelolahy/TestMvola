<?php

namespace App\Domain\DTOs\Paiement;

class PaiementInputDTO
{
    public int $id_reservation;
    public float $montant;
    public string $date_paiement;
    public string $mode_paiement;
    public string $status;

    public function __construct(
        int $id_reservation,
        float $montant,
        string $date_paiement,
        string $mode_paiement,
        string $status 
    ) {
        $this->id_reservation = $id_reservation;
        $this->montant = $montant;
        $this->date_paiement = $date_paiement;
        $this->mode_paiement = $mode_paiement;
        $this->status = $status;
    }
}
