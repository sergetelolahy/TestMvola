<?php

namespace App\Application\UseCases\Chambre;

use App\Domain\Contracts\ChambreRepository;

class DeleteChambre {
    public ChambreRepository $repository;

    public function __construct(ChambreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }
}