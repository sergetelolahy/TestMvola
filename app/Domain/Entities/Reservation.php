<?php

namespace App\Domain\Entities;

class Reservation
{
    public int $id;
    public int $id_client;
    public int $id_chambre;
    public string $date_debut;
    public string $date_fin;
    public string $statut;
    public ?string $tarif_template;
    public string $date_creation;
    public ?string $check_in_time;
    public ?string $check_out_time;

    // Ajoute ces deux propriétés
    public ?object $client = null;
    public ?object $chambre = null;

    public function __construct(
        int $id,
        int $id_client,
        int $id_chambre,
        string $date_debut,
        string $date_fin,
        string $statut,
        ?string $tarif_template,
        string $date_creation,
        ?string $check_in_time = null,
        ?string $check_out_time = null,
        ?object $client = null,
        ?object $chambre = null
    ) {
        $this->id = $id;
        $this->id_client = $id_client;
        $this->id_chambre = $id_chambre;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;
        $this->tarif_template = $tarif_template;
        $this->date_creation = $date_creation;
        $this->check_in_time = $check_in_time;
        $this->check_out_time = $check_out_time;
        $this->client = $client;
        $this->chambre = $chambre;
    }

    // Méthodes métier
    public function peutCheckIn(): bool
    {
        return $this->statut === 'confirmée' && $this->check_in_time === null;
    }

    public function peutCheckOut(): bool
    {
        return $this->check_in_time !== null && $this->check_out_time === null;
    }

    public function effectuerCheckIn(): void
    {
        if (!$this->peutCheckIn()) {
            throw new \InvalidArgumentException("Check-in non autorisé");
        }
        $this->check_in_time = date('Y-m-d H:i:s');
        $this->statut = 'en_cours';
    }

    public function effectuerCheckOut(): void
    {
        if (!$this->peutCheckOut()) {
            throw new \InvalidArgumentException("Check-out non autorisé");
        }
        $this->check_out_time = date('Y-m-d H:i:s');
        $this->statut = 'terminée';
    }

    public function estActive(): bool
    {
        return in_array($this->statut, ['confirmée', 'en_cours']);
    }

    public function getDureeSejour(): int
    {
        $debut = new \DateTime($this->date_debut);
        $fin = new \DateTime($this->date_fin);
        return $debut->diff($fin)->days;
    }
}