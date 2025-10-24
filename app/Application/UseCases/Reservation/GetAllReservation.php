<?php

namespace App\Application\UseCases\Reservation;

use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationOutputDTO;

class GetAllReservation{
    public ReservationRepository $repository;

    public function __construct(ReservationRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
    {
      $entities = $this->repository->getAll();
      return array_map(fn($e) => new ReservationOutputDTO(
        $e->id,
        $e->id_client,
        $e->id_chambre,
        $e->date_debut,
        $e->date_fin,
        $e->statut,
        $e->tarif_template,
        $e->date_creation,
        $e->check_in_time,
        $e->check_out_time,
        $e->client,   // Passer l'objet client
        $e->chambre   // Passer l'objet chambre
    ), $entities);
    }
}
