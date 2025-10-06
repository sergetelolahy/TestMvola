<?php

namespace App\Domain\Entities;

class Service
{
    public int $id;
    public string $nom;
    public string $description;

    public function __construct(int $id,string $nom,string $description) 
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }
}