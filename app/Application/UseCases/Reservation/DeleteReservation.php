<?php

namespace App\Application\UseCases\Reservation;

use App\Domain\Contracts\ReservationRepository;

class DeleteReservation {
    public ReservationRepository $repository;

    public function __construct(ReservationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }

}