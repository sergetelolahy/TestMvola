<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\ServiceDTO;
use App\Domain\Entities\Service;

interface ServiceRepository {
    public function getAll(): array;
    public function findByid(int $id): ?Service;
    public function save(ServiceDTO $dto ): Service;
    public function update(int $id,ServiceDTO $dto): Service;
    public function delete(int $id): bool;
}