<?php

namespace App\Infrastructure\Repositories;

use App\Models\ChambreModel;
use App\Domain\Entities\Chambre;
use App\Domain\Contracts\ChambreRepository;
use App\Domain\DTOs\Chambre\ChambreInputDTO;
use Exception;



class EloquentChambreRepository implements ChambreRepository
{
    public function getAll(): array {
        $chambres = ChambreModel::with('typechambre')->get();

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
         
        $m = ChambreModel::create([
            "numero" => $dto->numero,
            "prix" => $dto->prix, 
            "typechambre_id" => $dto->typechambre_id
        ]);
    
        // Recharger la relation typechambre
        $m->load('typechambre');
    
        return [
            'id' => $m->id,
            'numero' => $m->numero,
            'prix' => $m->prix,
            'estPrive' => $m->estPrive,
            'typechambre_id' => $m->typechambre_id,
            'typeChambreNom' => $m->typechambre->nom,
            'type' => [
                'id' => $m->typechambre->id,
                'nom' => $m->typechambre->nom,
                'nbrLit' => $m->typechambre->nbrLit,
                'maxPersonnes' => $m->typechambre->maxPersonnes,
                'description' => $m->typechambre->description,
            ],
        ];
    }

    public function update(int $id,ChambreInputDTO $dto): array {

          $m = ChambreModel::find($id);
        if (!$m) {
            return null;
        }

        $m->update([
            'numero' => $dto->numero,
            'prix' => $dto->prix,
            'typechambre_id' => $dto->typechambre_id
        ]);

        // 🔥 Recharger la relation typechambre pour avoir ses infos
        $m->load('typechambre');

        return [
            'id' => $m->id,
            'numero' => $m->numero,
            'prix' => $m->prix,
            'estPrive' => $m->estPrive,
            'typechambre_id' => $m->typechambre_id,
            'typeChambreNom' => $m->typechambre->nom,
            'type' => [
                'id' => $m->typechambre->id,
                'nom' => $m->typechambre->nom,
                'nbrLit' => $m->typechambre->nbrLit,
                'maxPersonnes' => $m->typechambre->maxPersonnes,
                'description' => $m->typechambre->description,
            ],
        ];
    }

    public function delete(int $id): bool {
        return ChambreModel::destroy($id) > 0;
    }
}