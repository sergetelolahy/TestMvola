<?php

namespace App\Domain\DTOs\Reservation;

use Carbon\Carbon;

class ReservationInputDTO
{
    public int $id_client;
    public int $id_chambre;
    public string $date_debut;
    public string $date_fin;
    public array $chambres;

    public function __construct(
        int $id_client,
        $id_chambre,
        string $date_debut,
        string $date_fin,
        array $chambres = []
    ) {
        $this->id_client = $id_client;
        $this->id_chambre = (int) $id_chambre;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->chambres = $this->parseChambres($chambres, $this->id_chambre);
    }

    private function parseChambres(array $chambres, int $defaultChambre): array
    {
        if (empty($chambres)) {
            return [$defaultChambre];
        }

        return array_map('intval', $chambres);
    }

    public function getNuits(): int
    {
        $debut = Carbon::parse($this->date_debut);
        $fin = Carbon::parse($this->date_fin);
        return $debut->diffInDays($fin);
    }

    public function estValide(): bool
    {
        $debut = Carbon::parse($this->date_debut);
        $fin = Carbon::parse($this->date_fin);
        return $fin->greaterThan($debut);
    }

    public function getChambreIds(): array
    {
        return $this->chambres;
    }

    public function getFirstChambreId(): int
    {
        return $this->chambres[0] ?? $this->id_chambre;
    }
}