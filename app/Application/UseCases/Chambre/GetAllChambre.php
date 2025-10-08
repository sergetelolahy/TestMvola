<?php

namespace App\Application\UseCases\Chambre;

use App\Domain\Contracts\ChambreRepository;


class GetAllChambre {
    public ChambreRepository $repository;

    public function __construct(ChambreRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
    {
       return $this->repository->getAll();
    }
}