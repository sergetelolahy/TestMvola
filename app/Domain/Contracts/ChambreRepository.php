<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\Chambre;
use App\Domain\DTOs\Chambre\ChambreInputDTO;


interface ChambreRepository {
    public function getAll(): array;
    public function findByid(int $id): ?Chambre;
    public function save(ChambreInputDTO $dto ): array;
    public function update(int $id,ChambreInputDTO $dto): array;
    public function delete(int $id): bool;
}