<?php

namespace App\Domain\DTOs\Reservation;

use Illuminate\Support\Collection;

class ReservationOutputDTO
{
    public function __construct(
        public int $id,
        public int $id_client,
        public string $date_debut,
        public string $date_fin,
        public string $statut,
        public float $tarif_template,
        public string $date_creation,
        public ?string $check_in_time,
        public ?string $check_out_time,
        public $client = null,
        public $chambres = [] // ← Supprimer le type strict
    ) {}

    public function toArray(): array
    {
        // Convertir les chambres en tableau si c'est une Collection
        $chambresArray = $this->chambres instanceof Collection 
            ? $this->chambres->toArray() 
            : $this->chambres;

        return [
            'id' => $this->id,
            'id_client' => $this->id_client,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'statut' => $this->statut,
            'tarif_template' => $this->tarif_template,
            'date_creation' => $this->date_creation,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'client' => $this->client,
            'chambres' => $chambresArray,
        ];
    }
}