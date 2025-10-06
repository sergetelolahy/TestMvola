<?php

namespace App\Domain\Contracts;



use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Entities\TypeChambre;

interface TypeChambreRepository {
    public function getAll(): array;
    public function findByid(int $id): ?TypeChambre;
    public function save(TypeChambreDTO $dto ): TypeChambre;
    public function update(int $id,TypeChambreDTO $dto): TypeChambre;
    public function delete(int $id): bool;
}