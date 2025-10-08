<?php

namespace App\Domain\DTOs\Chambre;

class ChambreOutputDTO
{
    public int $id;
    public string $numero;
    public float $prix;
    public int $typechambre_id;

    public function __construct(
        int $id,
        string $numero,
        float $prix,
        int $typechambre_id
    ) {
        $this->id = $id;  
        $this->numero = $numero;
        $this->prix = $prix;
        $this->typechambre_id = $typechambre_id;
    }
}