<?php

namespace App\Infrastructure\Repositories;

use services;
use Exception;
use App\Models\ChambreModel;
use App\Domain\Entities\Chambre;
use App\Domain\Contracts\ChambreRepository;
use App\Domain\DTOs\Chambre\ChambreInputDTO;



class EloquentChambreRepository implements ChambreRepository
{
    public function getAll(): array {
        $chambres = ChambreModel::with(['typechambre', 'services'])->get();
    
        return $chambres->map(fn($c) => [
            'id' => $c->id,
            'numero' => $c->numero,
            'prix' => $c->prix,
            'type' => [
                'id' => $c->typechambre->id,
                'nom' => $c->typechambre->nom,
                'nbrLit' => $c->typechambre->nbrLit,
                'maxPersonnes' => $c->typechambre->maxPersonnes,
                'description' => $c->typechambre->description,
            ],
            'services' => $c->services->map(fn($service) => [
                'id' => $service->id,
                'nom' => $service->nom,
                'description' => $service->description,
            ])->toArray()
        ])->toArray();
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
}