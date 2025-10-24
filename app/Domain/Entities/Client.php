<?php

namespace App\Domain\Entities;

class Client {
  
    public int $id ;
    public string $nom;
    public string $prenom;
    public string $tel;
    public string $email;
    public string $cin;

    public function __construct(int $id,string $nom,string $prenom,string $tel,string $email,string $cin)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->tel = $tel;
        $this->email = $email;
        $this->cin = $cin;
    }
}