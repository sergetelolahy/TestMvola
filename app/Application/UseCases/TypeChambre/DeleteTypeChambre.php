<?php

namespace App\Application\UseCases\TypeChambre;

use App\Domain\Contracts\TypeChambreRepository;

class DeleteTypeChambre {
    public TypeChambreRepository $repository;

    public function __construct(TypeChambreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }
}