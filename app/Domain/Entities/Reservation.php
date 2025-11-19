<?php

namespace App\Domain\Entities;

use Carbon\Carbon;

class Reservation
{
    public function __construct(
        public ?int $id,
        public int $id_client,
        public string $date_debut,
        public string $date_fin,
        public string $statut,
        public float $tarif_template,
        public string $date_creation,
        public ?string $check_in_time,
        public ?string $check_out_time,
        public $client = null,
        public $chambres = []
    ) {}

    public function getNuits(): int
    {
        $debut = Carbon::parse($this->date_debut);
        $fin = Carbon::parse($this->date_fin);
        return $debut->diffInDays($fin);
    }

    public function estAnnulable(): bool
    {
        return $this->statut === 'confirmée' 
            && Carbon::parse($this->date_debut)->gt(now()->addDays(1));
    }

    public function toArray(): array
    {
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
            'nuits' => $this->getNuits(),
            'client' => $this->client,
            'chambres' => $this->chambres,
        ];
    }
}