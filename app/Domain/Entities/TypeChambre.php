<?php

namespace App\Domain\Entities;

class TypeChambre
{
    public int $id;
    public string $nom;
    public int $nbrLit;
    public int $maxPersonnes;
    public ?string $description = null;
    public ?string $image = null; // ✅ On initialise par défaut à null

    public function __construct(
        int $id,
        string $nom,
        int $nbrLit,
        int $maxPersonnes,
        ?string $description = null, // ✅ Autorise null
        ?string $image = null        // ✅ Autorise null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->nbrLit = $nbrLit;
        $this->maxPersonnes = $maxPersonnes;
        $this->description = $description;
        $this->image = $image;
    }
}
