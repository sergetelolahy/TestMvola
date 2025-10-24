<?php 

namespace App\Application\UseCases\Paiement;

use App\Domain\Contracts\PaiementRepository;
use App\Domain\DTOs\Paiement\PaiementInputDTO;


class CreatePaiement {
    private PaiementRepository $Repository;

    public function __construct(PaiementRepository $Repository)
    {
      $this->Repository = $Repository;
    }

    public function execute(PaiementInputDTO $dto)
    {
      return $this->Repository->save($dto);
    }
}