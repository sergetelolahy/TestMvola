<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\Service\ServiceInputDTO;
use App\Domain\DTOs\ServiceDTO;
use App\Domain\Entities\Service;

interface ServiceRepository {
    public function getAll(): array;
    public function findByid(int $id): ?Service;
    public function save(ServiceInputDTO $dto ): Service;
    public function update(int $id,ServiceInputDTO $dto): Service;
    public function delete(int $id): bool;
}