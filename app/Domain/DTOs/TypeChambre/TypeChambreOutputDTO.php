<?php

namespace App\Domain\DTOs\TypeChambre;

class TypeChambreOutputDTO
{
    public int $id;
    public string $nom;
    public int $nbrLit;
    public int $maxPersonnes;
    public ?string $description;
    public ?string $image;

    public function __construct(
        int $id,
        string $nom,
        int $nbrLit,
        int $maxPersonnes,
        ?string $description,
        ?string $image
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->nbrLit = $nbrLit;
        $this->maxPersonnes = $maxPersonnes;
        $this->description = $description;
        $this->image = $image;
    }
}
