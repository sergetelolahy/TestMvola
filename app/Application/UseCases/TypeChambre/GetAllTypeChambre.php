<?php 

namespace App\Application\UseCases\TypeChambre;

use App\Domain\DTOs\TypeChambreDTO;
use App\Domain\Contracts\TypeChambreRepository;
use App\Domain\DTOs\TypeChambre\TypeChambreOutputDTO;

class GetAllTypeChambre {
    public TypeChambreRepository $repository;

    public function __construct(TypeChambreRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
    {
      $entities = $this->repository->getAll();
      return array_map(fn($e) => new TypeChambreOutputDTO($e->id,$e->nom,$e->nbrLit,$e->maxPersonnes,$e->description),$entities);
    }
}