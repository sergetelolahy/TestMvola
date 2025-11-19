<?php

namespace App\Infrastructure\Repositories;

use DateTime;
use services;
use Exception;
use App\Models\ChambreModel;
use Illuminate\Support\Carbon;
use App\Domain\Entities\Chambre;
use App\Models\ReservationModel;
use Illuminate\Support\Facades\DB;
use App\Domain\Contracts\ChambreRepository;
use App\Domain\DTOs\Chambre\ChambreInputDTO;



class EloquentChambreRepository implements ChambreRepository
{
    public function getAll(): array {
        $aujourdhui = Carbon::now()->format('Y-m-d');
        
        // CORRECTION : Récupérer les IDs des chambres réservées via la table pivot
        $chambresReservees = ReservationModel::where(function($query) use ($aujourdhui) {
                $query->where('date_debut', '<=', $aujourdhui)
                      ->where('date_fin', '>=', $aujourdhui);
            })
            ->whereIn('statut', ['confirmée', 'en cours', 'check-in'])
            ->with('chambres') // Charger les relations chambres
            ->get()
            ->pluck('chambres') // Récupérer toutes les collections de chambres
            ->flatten() // Aplatir en une seule collection
            ->pluck('id') // Récupérer les IDs
            ->unique()
            ->toArray();
    
        $chambres = ChambreModel::with(['typechambre', 'services'])->get();
    
        return $chambres->map(function($c) use ($chambresReservees) {
            $estOccupee = in_array($c->id, $chambresReservees);
            
            return [
                'id' => $c->id,
                'numero' => $c->numero,
                'prix' => $c->prix,
                'estPrive' => $c->estPrive,
                'status' => $estOccupee ? 'occupée' : 'libre',
                'type' => [
                    'id' => $c->typechambre->id,
                    'nom' => $c->typechambre->nom,
                    'nbrLit' => $c->typechambre->nbrLit,
                    'maxPersonnes' => $c->typechambre->maxPersonnes,
                    'description' => $c->typechambre->description,
                    'image' => $c->typechambre->image
                ],
                'services' => $c->services->map(function($service) {
                    return [
                        'id' => $service->id,
                        'nom' => $service->nom,
                        'description' => $service->description,
                    ];
                })->toArray()
            ];
        })->toArray();
    }

    public function findById(int $id): ?Chambre {
        $m = ChambreModel::find($id);
        return $m ? new Chambre($m->id, $m->numero, $m->prix , $m->typechambre_id) : null;
    }

    public function save(ChambreInputDTO $dto): array {
        // Vérifier si le numéro existe déjà
        $existingChambre = ChambreModel::where('numero', $dto->numero)->first();
        if ($existingChambre) {
            throw new Exception("Le numéro de chambre '{$dto->numero}' est déjà utilisé.");
        }
        
        // Créer la chambre
        $m = ChambreModel::create([
            "numero" => $dto->numero,
            "prix" => $dto->prix, 
            "typechambre_id" => $dto->typechambre_id
        ]);
    
        // 🔹 Attacher les services seulement s'ils existent
        if (!empty($dto->services)) {
            $m->services()->sync($dto->services); // lie les services
        }
    
        $m->load('typechambre', 'services');
    
        return [
            'id' => $m->id,
            'numero' => $m->numero,
            'prix' => $m->prix,
            'typechambre_id' => $m->typechambre_id,
            'typeChambreNom' => $m->typechambre->nom,
            'services' => $m->services->map(fn($s) => [
                'id' => $s->id,
                'nom' => $s->nom,
                'description' => $s->description
            ])->toArray(),
            'type' => [
                'id' => $m->typechambre->id,
                'nom' => $m->typechambre->nom,
                'nbrLit' => $m->typechambre->nbrLit,
                'maxPersonnes' => $m->typechambre->maxPersonnes,
                'description' => $m->typechambre->description,
            ],
        ];
    }
     
    public function findByIdWithDetails(int $id): ?array {
    $m = ChambreModel::with(['typechambre', 'services'])->find($id);
    if (!$m) {
        return null;
    }

    return [
        'id' => $m->id,
        'numero' => $m->numero,
        'prix' => $m->prix,
        'type' => [
            'id' => $m->typechambre->id,
            'nom' => $m->typechambre->nom,
            'nbrLit' => $m->typechambre->nbrLit,
            'maxPersonnes' => $m->typechambre->maxPersonnes,
            'description' => $m->typechambre->description,
        ],
        'services' => $m->services->map(fn($service) => [
            'id' => $service->id,
            'nom' => $service->nom,
            'description' => $service->description,
        ])->toArray()
    ];
}

public function update(int $id, ChambreInputDTO $dto): array {
    $m = ChambreModel::find($id);
    if (!$m) {
        throw new Exception("Chambre non trouvée");
    }

    // Vérifier si le numéro existe déjà (sauf pour la chambre actuelle)
    $existingChambre = ChambreModel::where('numero', $dto->numero)
        ->where('id', '!=', $id)
        ->first();
    if ($existingChambre) {
        throw new Exception("Le numéro de chambre '{$dto->numero}' est déjà utilisé.");
    }

    // 🔹 Mettre à jour les informations de base
    $m->update([
        'numero' => $dto->numero,
        'prix' => $dto->prix,
        'typechambre_id' => $dto->typechambre_id,
    ]);

    // 🔹 Mettre à jour les services
    if (!empty($dto->services)) {
        $m->services()->sync($dto->services);
    } else {
        // si tu veux garder les services actuels, commente cette ligne :
        $m->services()->sync([]);
    }

    // 🔹 Recharger les relations
    $m->load('typechambre', 'services');

    return [
        'id' => $m->id,
        'numero' => $m->numero,
        'prix' => $m->prix,
        'type' => [
            'id' => $m->typechambre->id,
            'nom' => $m->typechambre->nom,
            'nbrLit' => $m->typechambre->nbrLit,
            'maxPersonnes' => $m->typechambre->maxPersonnes,
            'description' => $m->typechambre->description,
            'image' => $m->typechambre->image
        ],
        'services' => $m->services->map(fn($service) => [
            'id' => $service->id,
            'nom' => $service->nom,
            'description' => $service->description,
        ])->toArray()
    ];
}


    public function delete(int $id): bool {
        return ChambreModel::destroy($id) > 0;
    }

    public function getChambresDisponibles(string $dateDebut, string $dateFin): array {
        try {
            // Convertir les dates en objets DateTime pour une comparaison précise
            $debutDemande = new DateTime($dateDebut);
            $finDemande = new DateTime($dateFin);
            
            // ⚠️ CORRECTION AMÉLIORÉE : Logique de chevauchement
            $chambresReservees = DB::table('chambre_reservation')
                ->join('reservations', 'chambre_reservation.reservation_id', '=', 'reservations.id')
                ->whereIn('reservations.statut', ['confirmée', 'en cours', 'check-in'])
                ->get()
                ->filter(function($reservation) use ($debutDemande, $finDemande) {
                    $debutReservation = new DateTime($reservation->date_debut);
                    $finReservation = new DateTime($reservation->date_fin);
                    
                    // Vérifier le chevauchement
                    // Une chambre est occupée si :
                    // - La réservation commence avant la fin demandée ET
                    // - La réservation se termine après le début demandé
                    // Mais on autorise le check-in le jour du check-out
                    return $debutReservation < $finDemande && $finReservation > $debutDemande;
                })
                ->pluck('chambre_id')
                ->unique()
                ->toArray();
    
            // Récupérer les chambres disponibles
            $chambres = ChambreModel::with(['typechambre', 'services'])
                ->whereNotIn('id', $chambresReservees)
                ->get();
    
            return $chambres->map(function($c) {
                return [
                    'id' => $c->id,
                    'numero' => $c->numero,
                    'prix' => $c->prix,
                    'type' => [
                        'id' => $c->typechambre->id,
                        'nom' => $c->typechambre->nom,
                        'nbrLit' => $c->typechambre->nbrLit,
                        'maxPersonnes' => $c->typechambre->maxPersonnes,
                        'description' => $c->typechambre->description,
                        'image' => $c->typechambre->image
                    ],
                    'services' => $c->services->map(fn($service) => [
                        'id' => $service->id,
                        'nom' => $service->nom,
                        'description' => $service->description,
                    ])->toArray()
                ];
            })->toArray();
    
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des chambres disponibles: " . $e->getMessage());
        }
    }

    public function getChambresDisponiblesAujourdhui(): array {
        try {
            $aujourdhui = Carbon::now()->format('Y-m-d');
            
            // ✅ CORRECT : Chambres occupées aujourd'hui = celles avec date_debut <= aujourd'hui ET date_fin > aujourd'hui
            $chambresReservees = DB::table('chambre_reservation')
                ->join('reservations', 'chambre_reservation.reservation_id', '=', 'reservations.id')
                ->where(function($query) use ($aujourdhui) {
                    $query->where('chambre_reservation.date_debut', '<=', $aujourdhui)
                          ->where('chambre_reservation.date_fin', '>', $aujourdhui);
                })
                ->whereIn('reservations.statut', ['confirmée', 'en cours', 'check-in'])
                ->pluck('chambre_reservation.chambre_id')
                ->unique()
                ->toArray();
         
            // Récupérer toutes les chambres
            $chambres = ChambreModel::with(['typechambre', 'services'])->get();
         
            return $chambres->map(function($c) use ($chambresReservees) {
                $estOccupee = in_array($c->id, $chambresReservees);
                return [
                    'id' => $c->id,
                    'numero' => $c->numero,
                    'prix' => $c->prix,
                    'estPrive' => $c->estPrive,
                    'status' => $estOccupee ? 'occupée' : 'libre',
                    'type' => [
                        'id' => $c->typechambre->id,
                        'nom' => $c->typechambre->nom,
                        'nbrLit' => $c->typechambre->nbrLit,
                        'maxPersonnes' => $c->typechambre->maxPersonnes,
                        'description' => $c->typechambre->description,
                        'image' => $c->typechambre->image
                    ],
                    'services' => $c->services->map(fn($service) => [
                        'id' => $service->id,
                        'nom' => $service->nom,
                        'description' => $service->description,
                    ])->toArray()
                ];
            })->toArray();
         
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des chambres: " . $e->getMessage());
        }
    }

}