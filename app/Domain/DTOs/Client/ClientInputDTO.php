<?php

namespace App\Domain\DTOs\Client;

class ClientInputDTO {
  
    public string $nom;
    public string $prenom;
    public string $tel;
    public string $email;
    public string $cin;

    public function __construct(string $nom,string $prenom,string $tel,string $email,string $cin)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->tel = $tel;
        $this->email = $email;
        $this->cin = $cin;
    }
}