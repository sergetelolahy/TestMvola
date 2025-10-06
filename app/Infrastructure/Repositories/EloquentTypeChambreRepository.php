<?php

namespace App\Infrastructure\Repositories;


use App\Models\TypeChambreModel;
use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Entities\TypeChambre;
use App\Domain\Contracts\TypeChambreRepository;

class EloquentTypeChambreRepository implements TypeChambreRepository
{
    public function getAll(): array {
        return TypeChambreModel::all()
            ->map(fn($m) => new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description))
            ->toArray();
    }

    public function findById(int $id): ?TypeChambre {
        $m = TypeChambreModel::find($id);
        return $m ? new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description) : null;
    }

    public function save(TypeChambreDTO $dto): TypeChambre {
        $m = TypeChambreModel::create(["nom" => $dto->nom,"nbrLit" => $dto->nbrLit,"maxPersonnes" => $dto->maxPersonnes, "description" => $dto->description]);
        return new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description);
    }

    public function update(int $id,TypeChambreDTO $dto): TypeChambre {
        $m = TypeChambreModel::find($id);
        $m->update([
            'nom' => $dto->nom,
            'nbrLit' => $dto->nbrLit,
            'maxPersonnes' => $dto->maxPersonnes,
            'description' => $dto->description
        ]);
        return new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description);
    }

    public function delete(int $id): bool {
        return TypeChambreModel::destroy($id) > 0;
    }
}