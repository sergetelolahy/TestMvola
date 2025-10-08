<?php

namespace App\Application\UseCases\TypeChambre;

use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Contracts\TypeChambreRepository;
use App\Domain\DTOs\TypeChambre\TypeChambreInputDTO;

class UpdateTypeChambre {
  public TypeChambreRepository $repository;
 
  public function __construct(TypeChambreRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,TypeChambreInputDTO $dto)
  {
    return $this->repository->update($id , $dto);
  }
}