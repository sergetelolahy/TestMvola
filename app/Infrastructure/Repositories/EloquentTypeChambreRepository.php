<?php

namespace App\Infrastructure\Repositories;


use App\Models\TypeChambreModel;
use App\Domain\Entities\TypeChambre;
use App\Domain\Contracts\TypeChambreRepository;
use App\Domain\DTOs\TypeChambre\TypeChambreInputDTO;

class EloquentTypeChambreRepository implements TypeChambreRepository
{
    public function getAll(): array {
        return TypeChambreModel::all()
            ->map(fn($m) => new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description,$m->image))
            ->toArray();
    }

    public function findById(int $id): ?TypeChambre {
        $m = TypeChambreModel::find($id);
        return $m ? new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description,$m->image) : null;
    }

    public function save(TypeChambreInputDTO $dto): TypeChambre {
        $m = TypeChambreModel::create(["nom" => $dto->nom,"nbrLit" => $dto->nbrLit,"maxPersonnes" => $dto->maxPersonnes, "description" => $dto->description, "image" => $dto->image]);
        return new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description,$m->image);
    }

    public function update(int $id,TypeChambreInputDTO $dto): TypeChambre {
        $m = TypeChambreModel::find($id);
        $m->update([
            'nom' => $dto->nom,
            'nbrLit' => $dto->nbrLit,
            'maxPersonnes' => $dto->maxPersonnes,
            'description' => $dto->description
        ]);
        return new TypeChambre($m->id, $m->nom, $m->nbrLit, $m->maxPersonnes, $m->description,$m->image);
    }

    public function delete(int $id): bool {
        return TypeChambreModel::destroy($id) > 0;
    }
}