<?php 

namespace App\Application\UseCases\Reservation;

use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationInputDTO;

class UpdateReservation {
    public ReservationRepository $repository;

    public function __construct(ReservationRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,ReservationInputDTO $dto)
  {
    return $this->repository->update($id , $dto);
  }
}