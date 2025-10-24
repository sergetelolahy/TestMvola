<?php

namespace App\Domain\Contracts;

use App\Domain\DTOs\Paiement\PaiementInputDTO;
use App\Domain\Entities\Paiement;

interface PaiementRepository {
    public function getAll(): array;
    public function findByid(int $id):? Paiement ;
    public function save(PaiementInputDTO $dto ): array;
    public function update(int $id,PaiementInputDTO $dto): Paiement;
    public function delete(int $id): bool;
}