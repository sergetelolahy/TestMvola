<?php

namespace App\Domain\Services;

use App\Domain\Entities\Reservation;

interface EmailServiceInterface
{
    public function sendReservationConfirmation(Reservation $reservation, string $clientEmail): bool;
    public function sendPaymentConfirmation(Reservation $reservation, string $clientEmail): bool;
    public function sendCheckInReminder(Reservation $reservation, string $clientEmail): bool;
}