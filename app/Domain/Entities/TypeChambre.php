<?php

namespace App\Domain\Entities;

class TypeChambre
{
    public int $id;
    public string $nom;
    public int $nbrLit;
    public int $maxPersonnes;
    public ?string $description = null;

    public function __construct(int $id,string $nom,int $nbrLit,int $maxPersonnes,string $description ) 
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->nbrLit = $nbrLit;
        $this->maxPersonnes = $maxPersonnes;
        $this->description = $description;
    }
}