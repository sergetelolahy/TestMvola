<?php
// app/Domain/UseCases/Chambre/GetChambresDisponiblesUseCase.php

namespace App\Application\UseCases\Chambre;

use App\Domain\Contracts\ChambreRepository;

class GetChambreDisponible
{
    public function __construct(
        private ChambreRepository $chambreRepository
    ) {}

    public function execute(string $dateDebut, string $dateFin): array
    {
        if (empty($dateDebut) || empty($dateFin)) {
            throw new \InvalidArgumentException('Les dates de début et de fin sont requises');
        }

        if ($dateDebut > $dateFin) {
            throw new \InvalidArgumentException('La date de début ne peut pas être après la date de fin');
        }

        return $this->chambreRepository->getChambresDisponibles($dateDebut, $dateFin);
    }

    public function executeAujourdhui(): array
    {
        return $this->chambreRepository->getChambresDisponiblesAujourdhui();
    }
}