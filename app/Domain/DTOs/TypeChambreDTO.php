<?php

namespace App\Domain\DTOs;

class TypeChambreDTO
{
    
    public string $nom;
    public int $nbrLit;
    public int $maxPersonnes;
    public ?string $description;

    public function __construct(string $nom,int $nbrLit,int $maxPersonnes,string $description ) 
    {
        
        $this->nom = $nom;
        $this->nbrLit = $nbrLit;
        $this->maxPersonnes = $maxPersonnes;
        $this->description = $description;
    }
}