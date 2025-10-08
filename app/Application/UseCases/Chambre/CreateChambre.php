<?php

namespace App\Application\UseCases\Chambre;


use App\Domain\Contracts\ChambreRepository;
use App\Domain\DTOs\Chambre\ChambreInputDTO;

class CreateChambre 
{
    public ChambreRepository $repository;

    public function __construct(ChambreRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ChambreInputDTO $dto)
    {
      return $this->repository->save($dto);
    }
}