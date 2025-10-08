<?php

namespace App\Domain\DTOs\Chambre;


class ChambreInputDTO
{
    public string $numero;
    public float $prix;
    public int $typechambre_id;

    public function __construct(
        string $numero,
        float $prix,
        int $typechambre_id
    ) {

        $this->numero = $numero;
        $this->prix = $prix;
        $this->typechambre_id = $typechambre_id;
    }
}