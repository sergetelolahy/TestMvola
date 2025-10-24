<?php

namespace App\Infrastructure\Repositories;

use Exception;
use App\Models\ChambreModel;
use App\Domain\Entities\Chambre;
use App\Models\ReservationModel;
use App\Domain\Entities\Reservation;
use App\Domain\Contracts\ReservationRepository;
use App\Domain\DTOs\Reservation\ReservationInputDTO;

class EloquentReservationRepository implements ReservationRepository
{
    public function getAll(): array {
        $reservations = ReservationModel::with(['client', 'chambre'])->get();
    
        return $reservations->map(fn($r) => (object)[
            'id' => $r->id,
            'id_client' => $r->id_client,
            'id_chambre' => $r->id_chambre,
            'date_debut' => $r->date_debut,
            'date_fin' => $r->date_fin,
            'statut' => $r->statut,
            'tarif_template' => $r->tarif_template,
            'date_creation' => $r->date_creation,
            'check_in_time' => $r->check_in_time,
            'check_out_time' => $r->check_out_time,
            'client' => (object)[
                'id' => $r->client->id,
                'nom' => $r->client->nom,
                'prenom' => $r->client->prenom,
                'email' => $r->client->email,
            ],
            'chambre' => (object)[
                'id' => $r->chambre->id,
                'numero' => $r->chambre->numero,
                'prix' => $r->chambre->prix,
            ]
        ])->toArray();
    }
    
    public function findById(int $id): ?Reservation {
        $r = ReservationModel::with(['client', 'chambre'])->find($id);
        return $r ? new Reservation(
            $r->id,
            $r->id_client,
            $r->id_chambre,
            $r->date_debut,
            $r->date_fin,
            $r->statut,
            $r->tarif_template,
            $r->date_creation,
            $r->check_in_time,
            $r->check_out_time
        ) : null;
    }

    public function save(ReservationInputDTO $dto): Reservation
    {   
         // 1. Récupérer la chambre et son prix
         $chambre = ChambreModel::find($dto->id_chambre);
         if (!$chambre) {
             throw new Exception("Chambre non trouvée.");
         }

         $prixNuit = $chambre->prix;

         // 2. Calculer le nombre de nuits et le montant total
        $dateDebut = \Carbon\Carbon::parse($dto->date_debut);
        $dateFin = \Carbon\Carbon::parse($dto->date_fin);
        $nuits = $dateDebut->diffInDays($dateFin);
        $montantTotal = $nuits * $prixNuit;

         // 3. Vérifier les conflits de réservation
    $existingReservation = ReservationModel::where('id_chambre', $dto->id_chambre)
        ->where('statut', '!=', 'annulée')
        ->where(function($query) use ($dto) {
            $query->where(function($q) use ($dto) {
                $q->where('date_debut', '<', $dto->date_fin)
                  ->where('date_fin', '>', $dto->date_debut);
            });
        })
        ->first();

    if ($existingReservation) {
        throw new Exception("La chambre n'est pas disponible pour ces dates/horaires.");
    }
    
    $r = ReservationModel::create([
        "id_client" => $dto->id_client,
        "id_chambre" => $dto->id_chambre,
        "date_debut" => $dto->date_debut,
        "date_fin" => $dto->date_fin,
        "statut" => 'en_attente',
        "tarif_template" => $montantTotal,
        "date_creation" => now(),
    ]);
    
        $r->load('client', 'chambre');
    
        // Retourner en tant qu'entité
        // 5. Retourner en tant qu'entité
        return new Reservation(
            $r->id,
            $r->id_client,
            $r->id_chambre,
            $r->date_debut,
            $r->date_fin,
            $r->statut,
            $r->tarif_template,
            $r->date_creation,
            $r->check_in_time,
            $r->check_out_time,
            $r->client,     // Ajoute ceci
            $r->chambre     // Et ceci
        );
    }
    

    public function update(int $id, ReservationInputDTO $dto): Reservation {
        $r = ReservationModel::find($id);
        if (!$r) {
            throw new Exception("Réservation non trouvée");
        }

        // Vérifier les conflits de réservation (exclure la réservation actuelle)
        $existingReservation = ReservationModel::where('id_chambre', $dto->id_chambre)
            ->where('id', '!=', $id)
            ->where(function($query) use ($dto) {
                $query->whereBetween('date_debut', [$dto->date_debut, $dto->date_fin])
                      ->orWhereBetween('date_fin', [$dto->date_debut, $dto->date_fin]);
            })
            ->where('statut', '!=', 'annulée')
            ->first();
        
        if ($existingReservation) {
            throw new Exception("La chambre n'est pas disponible pour ces dates.");
        }

        $r->update([
            'id_client' => $dto->id_client,
            'id_chambre' => $dto->id_chambre,
            'date_debut' => $dto->date_debut,
            'date_fin' => $dto->date_fin,
            // 'tarif_template' => $dto->tarif_template
        ]);

        $r->load('client', 'chambre');

        return new Reservation(
            $r->id,
            $r->id_client,
            $r->id_chambre,
            $r->date_debut,
            $r->date_fin,
            $r->statut,
            $r->tarif_template,
            $r->date_creation,
            $r->check_in_time,
            $r->check_out_time
        );
    }

    public function delete(int $id): bool {
        return ReservationModel::destroy($id) > 0;
    }

    public function findByClient(int $clientId): array {
        $reservations = ReservationModel::with(['client', 'chambre'])
            ->where('id_client', $clientId)
            ->get();
    
        return $reservations->map(fn($r) => [
            'id' => $r->id,
            'id_client' => $r->id_client,
            'id_chambre' => $r->id_chambre,
            'date_debut' => $r->date_debut,
            'date_fin' => $r->date_fin,
            'statut' => $r->statut,
            'tarif_template' => $r->tarif_template,
            'date_creation' => $r->date_creation,
            'check_in_time' => $r->check_in_time,
            'check_out_time' => $r->check_out_time,
            'client' => [
                'id' => $r->client->id,
                'nom' => $r->client->nom,
                'prenom' => $r->client->prenom,
            ],
            'chambre' => [
                'id' => $r->chambre->id,
                'numero' => $r->chambre->numero,
            ]
        ])->toArray();
    }

    public function findByChambre(int $chambreId): array {
        $reservations = ReservationModel::with(['client', 'chambre'])
            ->where('id_chambre', $chambreId)
            ->get();
    
        return $reservations->map(fn($r) => [
            'id' => $r->id,
            'id_client' => $r->id_client,
            'id_chambre' => $r->id_chambre,
            'date_debut' => $r->date_debut,
            'date_fin' => $r->date_fin,
            'statut' => $r->statut,
            'tarif_template' => $r->tarif_template,
            'date_creation' => $r->date_creation,
            'check_in_time' => $r->check_in_time,
            'check_out_time' => $r->check_out_time,
            'client' => [
                'id' => $r->client->id,
                'nom' => $r->client->nom,
                'prenom' => $r->client->prenom,
            ]
        ])->toArray();
    }

    public function findReservationsActives(): array {
        $reservations = ReservationModel::with(['client', 'chambre'])
            ->whereIn('statut', ['confirmée', 'en_cours'])
            ->get();
    
        return $reservations->map(fn($r) => [
            'id' => $r->id,
            'id_client' => $r->id_client,
            'id_chambre' => $r->id_chambre,
            'date_debut' => $r->date_debut,
            'date_fin' => $r->date_fin,
            'statut' => $r->statut,
            'tarif_template' => $r->tarif_template,
            'date_creation' => $r->date_creation,
            'check_in_time' => $r->check_in_time,
            'check_out_time' => $r->check_out_time,
            'client' => [
                'id' => $r->client->id,
                'nom' => $r->client->nom,
                'prenom' => $r->client->prenom,
            ],
            'chambre' => [
                'id' => $r->chambre->id,
                'numero' => $r->chambre->numero,
            ]
        ])->toArray();
    }

    public function findReservationsByDates(string $startDate, string $endDate): array {
        $reservations = ReservationModel::with(['client', 'chambre'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('date_debut', [$startDate, $endDate])
                      ->orWhereBetween('date_fin', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('date_debut', '<=', $startDate)
                            ->where('date_fin', '>=', $endDate);
                      });
            })
            ->get();
    
        return $reservations->map(fn($r) => [
            'id' => $r->id,
            'id_client' => $r->id_client,
            'id_chambre' => $r->id_chambre,
            'date_debut' => $r->date_debut,
            'date_fin' => $r->date_fin,
            'statut' => $r->statut,
            'tarif_template' => $r->tarif_template,
            'date_creation' => $r->date_creation,
            'check_in_time' => $r->check_in_time,
            'check_out_time' => $r->check_out_time,
            'client' => [
                'id' => $r->client->id,
                'nom' => $r->client->nom,
                'prenom' => $r->client->prenom,
            ],
            'chambre' => [
                'id' => $r->chambre->id,
                'numero' => $r->chambre->numero,
            ]
        ])->toArray();
    }
}