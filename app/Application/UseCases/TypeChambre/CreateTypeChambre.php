<?php 

namespace App\Application\UseCases\TypeChambre;

use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Contracts\TypeChambreRepository;
use App\Domain\DTOs\TypeChambre\TypeChambreInputDTO;


class CreateTypeChambre {
    private TypeChambreRepository $Repository;

    public function __construct(TypeChambreRepository $Repository)
    {
      $this->Repository = $Repository;
    }

    public function execute(TypeChambreInputDTO $dto)
    {
      return $this->Repository->save($dto);
    }
}