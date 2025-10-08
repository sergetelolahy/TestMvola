<?php

namespace App\Domain\DTOs;

class ChambreDTO
{
    public string $numero;
    public float $prix;
    public string $statut;
    public int $typechambre_id;

    public function __construct(
        string $numero,
        float $prix,
        string $statut,
        int $typechambre_id
    ) {

        $this->numero = $numero;
        $this->prix = $prix;
        $this->statut = $statut;
        $this->typechambre_id = $typechambre_id;
    }
}