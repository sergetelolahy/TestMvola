<?php 

namespace App\Application\UseCases\Paiement;

use App\Domain\Contracts\PaiementRepository;
use App\Domain\Contracts\TypeChambreRepository;
use App\Domain\DTOs\Paiement\PaiementOutputDTO;
use App\Domain\DTOs\TypeChambre\TypeChambreOutputDTO;

class GetAllPaiement {
    public PaiementRepository $repository;

    public function __construct(PaiementRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
{
    $entities = $this->repository->getAll();

    return array_map(
        fn($e) => new PaiementOutputDTO(
            $e->id,
            $e->id_reservation,
            $e->montant,
            $e->date_paiement,
            $e->mode_paiement,
            $e->status
        ),
        $entities
    );
}
}