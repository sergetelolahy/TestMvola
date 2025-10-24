<?php

namespace App\Application\UseCases\Paiement;

use App\Domain\Contracts\PaiementRepository;
use App\Domain\DTOs\Paiement\PaiementInputDTO;

class UpdatePaiement {
  public PaiementRepository $repository;
 
  public function __construct(PaiementRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,PaiementInputDTO $dto)
  {
    return $this->repository->update($id , $dto);
  }
}