<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\Client\ClientInputDTO;
use App\Domain\Entities\Client;

interface ClientRepository {
    public function getAll(): array;
    public function findByid(int $id):? Client ;
    public function save(ClientInputDTO $dto ): Client;
    public function update(int $id,ClientInputDTO $dto): Client;
    public function delete(int $id): bool;
}