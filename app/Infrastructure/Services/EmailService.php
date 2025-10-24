<?php

namespace App\Infrastructure\Services;

use App\Mail\CheckInReminderMail;
use Illuminate\Support\Facades\Log;
use App\Domain\Entities\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmationMail;
use App\Mail\ReservationConfirmationMail;
use App\Domain\Services\EmailServiceInterface;



class EmailService implements EmailServiceInterface {
    public function sendReservationConfirmation(Reservation $reservation, string $clientEmail): bool
    {
        try {
            Mail::to($clientEmail)->send(new ReservationConfirmationMail($reservation));
            Log::info("Email de confirmation envoyé à {$clientEmail} pour la réservation #{$reservation->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email confirmation réservation: " . $e->getMessage());
            return false;
        }
    }

    public function sendPaymentConfirmation(Reservation $reservation, string $clientEmail): bool
    {
        try {
            Mail::to($clientEmail)->send(new PaymentConfirmationMail($reservation));
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email confirmation paiement: " . $e->getMessage());
            return false;
        }
    }

    public function sendCheckInReminder(Reservation $reservation, string $clientEmail): bool
    {
        try {
            Mail::to($clientEmail)->send(new CheckInReminderMail($reservation));
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur envoi email rappel check-in: " . $e->getMessage());
            return false;
        }
    }
}