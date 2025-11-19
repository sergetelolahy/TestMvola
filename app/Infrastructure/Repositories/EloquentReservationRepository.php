<?php

namespace App\Infrastructure\Repositories;

use Exception;
use App\Models\ChambreModel;
use App\Domain\Entities\Chambre;
use App\Models\ReservationModel;
use Illuminate\Support\Facades\DB;
use App\Domain\Entities\Reservation;
use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationInputDTO;

class EloquentReservationRepository implements ReservationRepository
{
    public function getAll(): array {
        $reservations = ReservationModel::with(['client', 'chambres'])->get();
        return $reservations->map(fn($r) => $this->toEntity($r))->toArray();
    }
    
    public function findById(int $id): ?Reservation {
        $r = ReservationModel::with(['client', 'chambres'])->find($id);
        if (!$r) {
            return null;
        }

        return new Reservation(
            $r->id,
            $r->id_client,
            $r->date_debut,
            $r->date_fin,
            $r->statut,
            $r->tarif_template,
            $r->date_creation,
            $r->check_in_time,
            $r->check_out_time,
            $r->client,
            $r->chambres
        );
    }

    public function save(ReservationInputDTO $dto): Reservation
    {   
        return DB::transaction(function () use ($dto) {
            // 1. Récupérer toutes les chambres
            $chambres = ChambreModel::whereIn('id', $dto->getChambreIds())->get();
            if ($chambres->count() != count($dto->getChambreIds())) {
                throw new Exception("Une ou plusieurs chambres n'ont pas été trouvées.");
            }
    
            // 2. Calculer le nombre de nuits et le montant total
            $nuits = $dto->getNuits();
            $montantTotal = 0;
            foreach ($chambres as $chambre) {
                $montantTotal += $chambre->prix * $nuits;
            }
    
            // 3. Vérifier les conflits de réservation
            foreach ($dto->getChambreIds() as $chambreId) {
                $existingReservation = DB::table('chambre_reservation')
                    ->join('reservations', 'chambre_reservation.reservation_id', '=', 'reservations.id')
                    ->where('chambre_reservation.chambre_id', $chambreId)
                    ->where('reservations.statut', '!=', 'annulée')
                    ->where(function($query) use ($dto) {
                        $query->where('chambre_reservation.date_debut', '<', $dto->date_fin)
                              ->where('chambre_reservation.date_fin', '>', $dto->date_debut);
                    })
                    ->exists();
    
                if ($existingReservation) {
                    throw new Exception("La chambre ID $chambreId n'est pas disponible pour ces dates.");
                }
            }
    
            // 4. Créer la réservation
            $reservation = ReservationModel::create([
                "id_client" => $dto->id_client,
                "date_debut" => $dto->date_debut,
                "date_fin" => $dto->date_fin,
                "statut" => 'confirmée',
                "tarif_template" => $montantTotal,
                "date_creation" => now(),
            ]);
    
            // 5. Attacher les chambres
            $pivotData = [];
            foreach ($chambres as $chambre) {
                $pivotData[$chambre->id] = [
                    'date_debut' => $dto->date_debut,
                    'date_fin' => $dto->date_fin,
                    'prix' => $chambre->prix * $nuits,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $reservation->chambres()->attach($pivotData);
    
            // 6. Charger les relations
            $reservation->load('client', 'chambres');
    
            return $this->toEntity($reservation);
        });
    }

    public function update(int $id, ReservationInputDTO $dto): Reservation {
        return DB::transaction(function () use ($id, $dto) {
            $reservation = ReservationModel::with(['client', 'chambres'])->find($id);
            if (!$reservation) {
                throw new Exception("Réservation non trouvée");
            }

            // 1. Récupérer les chambres
            $chambres = ChambreModel::whereIn('id', $dto->getChambreIds())->get();
            if ($chambres->count() != count($dto->getChambreIds())) {
                throw new Exception("Une ou plusieurs chambres n'ont pas été trouvées.");
            }

            // 2. Calculer le nombre de nuits et le montant total
            $nuits = $dto->getNuits();
            $montantTotal = 0;
            foreach ($chambres as $chambre) {
                $montantTotal += $chambre->prix * $nuits;
            }

            // 3. Vérifier les conflits de réservation (exclure la réservation actuelle)
            foreach ($dto->getChambreIds() as $chambreId) {
                $existingReservation = DB::table('chambre_reservation')
                    ->join('reservations', 'chambre_reservation.reservation_id', '=', 'reservations.id')
                    ->where('chambre_reservation.chambre_id', $chambreId)
                    ->where('reservations.id', '!=', $id)
                    ->where('reservations.statut', '!=', 'annulée')
                    ->where(function($query) use ($dto) {
                        $query->where('chambre_reservation.date_debut', '<', $dto->date_fin)
                              ->where('chambre_reservation.date_fin', '>', $dto->date_debut);
                    })
                    ->exists();

                if ($existingReservation) {
                    throw new Exception("La chambre ID $chambreId n'est pas disponible pour ces dates.");
                }
            }

            // 4. Mettre à jour la réservation
            $reservation->update([
                "id_client" => $dto->id_client,
                "date_debut" => $dto->date_debut,
                "date_fin" => $dto->date_fin,
                "tarif_template" => $montantTotal,
            ]);

            // 5. Synchroniser les chambres
            $pivotData = [];
            foreach ($chambres as $chambre) {
                $pivotData[$chambre->id] = [
                    'date_debut' => $dto->date_debut,
                    'date_fin' => $dto->date_fin,
                    'prix' => $chambre->prix * $nuits,
                    'updated_at' => now(),
                ];
            }

            $reservation->chambres()->sync($pivotData);

            // 6. Recharger les relations
            $reservation->load('client', 'chambres');

            return $this->toEntity($reservation);
        });
    }

    public function delete(int $id): bool {
        $reservation = ReservationModel::find($id);
        if (!$reservation) {
            return false;
        }

        // Détacher toutes les chambres (la relation many-to-many)
        $reservation->chambres()->detach();

        // Supprimer la réservation
        return $reservation->delete();
    }

    public function findByClient(int $clientId): array {
        $reservations = ReservationModel::with(['client', 'chambres'])
            ->where('id_client', $clientId)
            ->get();
    
        return $reservations->map(fn($r) => $this->toEntity($r))->toArray();
    }

    public function findByChambre(int $chambreId): array {
        // On récupère les réservations qui ont la chambre avec l'id $chambreId via la table pivot
        $reservations = ReservationModel::with(['client', 'chambres'])
            ->whereHas('chambres', function ($query) use ($chambreId) {
                $query->where('chambres.id', $chambreId);
            })
            ->get();
    
        return $reservations->map(fn($r) => $this->toEntity($r))->toArray();
    }

    public function findReservationsActives(): array {
        $reservations = ReservationModel::with(['client', 'chambres'])
            ->whereIn('statut', ['confirmée', 'en cours']) // Note: vérifie les statuts exacts
            ->get();
    
        return $reservations->map(fn($r) => $this->toEntity($r))->toArray();
    }

    public function findReservationsByDates(string $startDate, string $endDate): array {
        $reservations = ReservationModel::with(['client', 'chambres'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('date_debut', [$startDate, $endDate])
                      ->orWhereBetween('date_fin', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('date_debut', '<=', $startDate)
                            ->where('date_fin', '>=', $endDate);
                      });
            })
            ->get();
    
        return $reservations->map(fn($r) => $this->toEntity($r))->toArray();
    }

    private function toEntity(ReservationModel $model): Reservation
    {
        return new Reservation(
            id: $model->id,
            id_client: $model->id_client,
            date_debut: $model->date_debut->format('Y-m-d H:i:s'),
            date_fin: $model->date_fin->format('Y-m-d H:i:s'),
            statut: $model->statut,
            tarif_template: (float) $model->tarif_template,
            date_creation: $model->date_creation->format('Y-m-d H:i:s'),
            check_in_time: $model->check_in_time?->format('Y-m-d H:i:s'),
            check_out_time: $model->check_out_time?->format('Y-m-d H:i:s'),
            client: $model->client,
            chambres: $model->chambres
        );
    }
}