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
        return array_map(function($e) {
            return new ReservationOutputDTO(
                $e->id,
                $e->id_client,
                $e->date_debut,
                $e->date_fin,
                $e->statut,
                $e->tarif_template,
                $e->date_creation,
                $e->check_in_time,
                $e->check_out_time,
                $e->client,
                $e->chambres->toArray() // ← Conversion en tableau
            );
            
        }, $entities);
    }
}
