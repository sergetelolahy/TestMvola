<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\ClientRepository;
use App\Domain\DTOs\Client\ClientInputDTO;
use App\Domain\Entities\Client;
use App\Models\ClientModel;

class EloquentClientRepository implements ClientRepository {
    public function getAll(): array {
        return ClientModel::all()
            ->map(fn($m) => new Client($m->id, $m->nom, $m->prenom, $m->tel, $m->email, $m->cin))
            ->toArray();
    }

    public function findById(int $id): ?Client {
        $m =  ClientModel::find($id);
        return $m ? new Client($m->id, $m->nom, $m->prenom, $m->tel, $m->email, $m->cin) :null;
    }

    public function save(ClientInputDTO $dto): Client {
        $m = ClientModel::create(["nom" => $dto->nom,"prenom" => $dto->prenom,"tel" => $dto->tel, "email" => $dto->email, "cin" => $dto->cin]);
        return new Client($m->id, $m->nom, $m->prenom, $m->tel, $m->email, $m->cin);
    }

    public function update(int $id,ClientInputDTO $dto): Client {
        $m = ClientModel::find($id);
        $m->update(["nom" => $dto->nom,"prenom" => $dto->prenom,"tel" => $dto->tel, "email" => $dto->email, "cin" => $dto->cin]);
        return new Client($m->id, $m->nom, $m->prenom, $m->tel, $m->email, $m->cin);
    }

    public function delete(int $id): bool {
        return ClientModel::destroy($id) > 0;
    }
}