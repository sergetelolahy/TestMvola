<?php 

namespace App\Domain\DTOs;

class ServiceDTO {
    public string $nom;
    public string $description;

    public function __construct(string $nom, string $description)
    {
        $this->nom = $nom;
        $this->description = $description;
    }
}