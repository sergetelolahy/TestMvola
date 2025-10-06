<?php

namespace App\Infrastructure\Repositories;

use App\Models\ServiceModel;
use App\Domain\Entities\Service;
use App\Domain\Contracts\ServiceRepository;
use App\Domain\DTOs\ServiceDTO;

class EloquentServiceRepository implements ServiceRepository{
    public function getAll(): array {
        return ServiceModel::all()
            ->map(fn($m) => new Service($m->id, $m->nom, $m->description))
            ->toArray();
    }

    public function findById(int $id): ?Service {
        $m = ServiceModel::find($id);
        return $m ? new Service($m->id, $m->nom, $m->description) : null;
    }

    public function save(ServiceDTO $dto): Service {
        $m = ServiceModel::create(["nom" => $dto->nom, "description" => $dto->description]);
        return new Service($m->id, $m->nom, $m->description);
    }

    public function update(int $id,ServiceDTO $dto): Service {
        $m = ServiceModel::find($id);
        $m->update([
            'nom' => $dto->nom,
            'description' => $dto->description
        ]);
        return new Service($m->id, $m->nom, $m->description);
    }

    public function delete(int $id): bool {
        return ServiceModel::destroy($id) > 0;
    }
}