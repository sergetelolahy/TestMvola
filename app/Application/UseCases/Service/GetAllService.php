<?php 

namespace App\Application\UseCases\Service;

use App\Domain\Contracts\ServiceRepository;
use App\Domain\DTOs\ServiceDTO;

class GetAllService {
    public ServiceRepository $repository;

    public function __construct(ServiceRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
    {
      $entities = $this->repository->getAll();
      return array_map(fn($e) => new ServiceDTO($e->id,$e->nom,$e->description),$entities);
    }
}