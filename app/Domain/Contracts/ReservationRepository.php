<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\Reservation\ReservationInputDTO;
use App\Domain\Entities\Reservation;

interface ReservationRepository
{
    public function getAll(): array;
    public function findById(int $id): ?Reservation;
    public function save(ReservationInputDTO $dto): Reservation;
    public function update(int $id, ReservationInputDTO $dto): Reservation;
    public function delete(int $id): bool;
    
    // Méthodes spécifiques aux réservations
    public function findByClient(int $clientId): array;
    public function findByChambre(int $chambreId): array;
    public function findReservationsActives(): array;
    public function findReservationsByDates(string $startDate, string $endDate): array;
}