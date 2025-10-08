<?php

namespace App\Application\UseCases\Chambre;

use App\Domain\Contracts\ChambreRepository;
use App\Domain\DTOs\Chambre\ChambreInputDTO;



class UpdateChambre {
  public ChambreRepository $repository;
 
  public function __construct(ChambreRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,ChambreInputDTO $dto)
  {
    return $this->repository->update($id, $dto);
  }
}