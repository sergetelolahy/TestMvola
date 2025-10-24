<?php

namespace App\Application\UseCases\Reservation;

use App\Domain\Entities\Reservation;
use App\Domain\Contracts\ClientRepository;

use App\Domain\Services\EmailServiceInterface;
use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationInputDTO;


class CreateReservation
{
    public ReservationRepository $repository;
    public ClientRepository $clientRepository;
    public EmailServiceInterface $emailService;

    public function __construct(
        ReservationRepository $repository,
        ClientRepository $clientRepository,
        EmailServiceInterface $emailService
    ) {
        $this->repository = $repository;
        $this->clientRepository = $clientRepository;
        $this->emailService = $emailService;
    }

    public function execute(ReservationInputDTO $dto): Reservation
    {
        $reservation = $this->repository->save($dto);

        // Récupérer le client via le clientRepository
        $client = $this->clientRepository->findById($dto->id_client);
        if ($client) {
            // Envoyer l'email de confirmation
            $this->emailService->sendReservationConfirmation($reservation, $client->email);
        }

        return $reservation;
    }
}