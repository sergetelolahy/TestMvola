<?php

namespace App\Application\UseCases\Reservation;

use App\Domain\Contracts\ClientRepository;
use App\Domain\Services\EmailServiceInterface;
use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationInputDTO;

class CreateReservation
{
    public function __construct(
        private ReservationRepository $repository,
        private ClientRepository $clientRepository,
        private EmailServiceInterface $emailService
    ) {}

    public function execute(ReservationInputDTO $dto)
    {
        // Valider les dates
        if (!$dto->estValide()) {
            throw new \Exception("Les dates de réservation sont invalides.");
        }

        $reservation = $this->repository->save($dto);

        // Envoyer l'email de confirmation
        $client = $this->clientRepository->findById($dto->id_client);
        if ($client && property_exists($client, 'email')) {
            $this->emailService->sendReservationConfirmation($reservation, $client->email);
        }

        return $reservation;
    }
}