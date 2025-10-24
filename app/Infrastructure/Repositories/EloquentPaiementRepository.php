<?php

namespace App\Infrastructure\Repositories;

use Exception;
use App\Models\PaiementModel;
use App\Models\ReservationModel;
use App\Domain\Entities\Paiement;
use App\Domain\Contracts\PaiementRepository;
use App\Domain\DTOs\Paiement\PaiementInputDTO;

class EloquentPaiementRepository implements PaiementRepository
{
    /**
     * Récupère tous les paiements avec les réservations associées
     */
    public function getAll(): array
    {
        $paiements = PaiementModel::with('reservation')->get();

        return $paiements->map(fn($p) => [
            'id' => $p->id,
            'id_reservation' => $p->id_reservation,
            'montant' => $p->montant,
            'date_paiement' => $p->date_paiement,
            'mode_paiement' => $p->mode_paiement,
            'status' => $p->status,
            'reservation' => $p->reservation ? [
                'id' => $p->reservation->id,
                'date_debut' => $p->reservation->date_debut,
                'date_fin' => $p->reservation->date_fin,
                'statut' => $p->reservation->statut,
                'tarif_template' => $p->reservation->tarif_template,
            ] : null
        ])->toArray();
    }

    /**
     * Trouver un paiement par son ID
     */
    public function findById(int $id): ?Paiement
    {
        $p = PaiementModel::with('reservation')->find($id);
        return $p ? new Paiement(
            $p->id,
            $p->id_reservation,
            $p->montant,
            $p->date_paiement,
            $p->mode_paiement,
            $p->status
        ) : null;
    }

    /**
     * Créer un nouveau paiement
     */
    public function save(PaiementInputDTO $dto): array
    {
        // Vérifier si la réservation existe
        $reservation = ReservationModel::with(['client', 'chambre'])->find($dto->id_reservation);
        if (!$reservation) {
            throw new \Exception("Réservation non trouvée pour ce paiement.");
        }
    
        // Créer le paiement
        $p = PaiementModel::create([
            'id_reservation' => $dto->id_reservation,
            'montant' => $dto->montant,
            'date_paiement' => $dto->date_paiement,
            'mode_paiement' => $dto->mode_paiement,
            'status' => $dto->status ?? 'en attente',
        ]);
    
        // Charger toutes les relations
        $p->load('reservation.client', 'reservation.chambre');
    
        // Retourner sous forme de tableau complet
        return [
            'id' => $p->id,
            'montant' => $p->montant,
            'date_paiement' => $p->date_paiement,
            'mode_paiement' => $p->mode_paiement,
            'status' => $p->status,
            'reservation' => [
                'id' => $p->reservation->id,
                'date_debut' => $p->reservation->date_debut,
                'date_fin' => $p->reservation->date_fin,
                'statut' => $p->reservation->statut,
                'client' => [
                    'id' => $p->reservation->client->id ?? null,
                    'nom' => $p->reservation->client->nom ?? null,
                    'prenom' => $p->reservation->client->prenom ?? null,
                    'tel' => $p->reservation->client->tel ?? null,
                    'email' => $p->reservation->client->email ?? null,
                ],
                'chambre' => [
                    'id' => $p->reservation->chambre->id ?? null,
                    'numero' => $p->reservation->chambre->numero ?? null,
                    'type' => $p->reservation->chambre->type ?? null,
                    'prix' => $p->reservation->chambre->prix ?? null,
                ],
            ],
        ];
    }
    

    /**
     * Mettre à jour un paiement existant
     */
    public function update(int $id, PaiementInputDTO $dto): Paiement
    {
        $p = PaiementModel::find($id);
        if (!$p) {
            throw new Exception("Paiement non trouvé.");
        }

        $p->update([
            'id_reservation' => $dto->id_reservation,
            'montant' => $dto->montant,
            'date_paiement' => $dto->date_paiement,
            'mode_paiement' => $dto->mode_paiement,
            'status' => $dto->status,
        ]);

        $p->load('reservation');

        return new Paiement(
            $p->id,
            $p->id_reservation,
            $p->montant,
            $p->date_paiement,
            $p->mode_paiement,
            $p->status
        );
    }

    /**
     * Supprimer un paiement
     */
    public function delete(int $id): bool
    {
        return PaiementModel::destroy($id) > 0;
    }

    /**
     * Récupérer tous les paiements d'une réservation donnée
     */
    public function findByReservation(int $reservationId): array
    {
        $paiements = PaiementModel::where('id_reservation', $reservationId)
            ->with('reservation')
            ->get();

        return $paiements->map(fn($p) => [
            'id' => $p->id,
            'id_reservation' => $p->id_reservation,
            'montant' => $p->montant,
            'date_paiement' => $p->date_paiement,
            'mode_paiement' => $p->mode_paiement,
            'status' => $p->status,
        ])->toArray();
    }

    /**
     * Récupérer les paiements partiels
     */
    public function findPaiementsPartiels(): array
    {
        return PaiementModel::where('status', 'partielle')->get()->toArray();
    }

    /**
     * Récupérer les paiements totaux
     */
    public function findPaiementsTotaux(): array
    {
        return PaiementModel::where('status', 'total')->get()->toArray();
    }
}
