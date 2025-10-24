<?php

namespace App\Application\UseCases\Paiement;

use App\Domain\Contracts\PaiementRepository;

class DeletePaiement {
    public PaiementRepository $repository;

    public function __construct(PaiementRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }
}