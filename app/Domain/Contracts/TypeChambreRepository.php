<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\TypeChambre;
use App\Domain\DTOs\TypeChambre\TypeChambreInputDTO;

interface TypeChambreRepository {
    public function getAll(): array;
    public function findByid(int $id): ?TypeChambre;
    public function save(TypeChambreInputDTO $dto ): TypeChambre;
    public function update(int $id,TypeChambreInputDTO $dto): TypeChambre;
    public function delete(int $id): bool;
}