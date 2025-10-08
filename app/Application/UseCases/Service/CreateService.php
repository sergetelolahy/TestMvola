<?php 

namespace App\Application\UseCases\Service;

use App\Domain\Contracts\ServiceRepository;
use App\Domain\DTOs\Service\ServiceInputDTO;

class CreateService {
  private ServiceRepository $repository;
  
  public function __construct(ServiceRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(ServiceInputDTO $dto)
  {
    return $this->repository->save($dto);
  }
}