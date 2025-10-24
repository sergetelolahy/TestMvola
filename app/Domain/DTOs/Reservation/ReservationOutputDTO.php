<?php

namespace App\Domain\DTOs\Reservation;

class ReservationOutputDTO
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
    public object $client; // ou vous pouvez créer un DTO pour Client aussi
    public object $chambre; // et pour Chambre

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
        object $client = null,  // Ajout de client
        object $chambre = null   // Ajout de chambre
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
}