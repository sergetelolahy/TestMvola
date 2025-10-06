<?php 

namespace App\Application\UseCases\Service;

use App\Domain\Contracts\ServiceRepository;
use App\Domain\DTOs\ServiceDTO;
use App\Domain\Entities\Service;

class CreateService {
  private ServiceRepository $repository;
  
  public function __construct(ServiceRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(ServiceDTO $dto)
  {
    return $this->repository->save($dto);
  }
}