<?php 

namespace App\Application\UseCases\TypeChambre;

use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Contracts\TypeChambreRepository;


class CreateTypeChambre {
    private TypeChambreRepository $Repository;

    public function __construct(TypeChambreRepository $Repository)
    {
      $this->Repository = $Repository;
    }

    public function execute(TypeChambreDTO $dto)
    {
      return $this->Repository->save($dto);
    }
}