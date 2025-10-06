<?php

namespace App\Application\UseCases\Service;

use App\Domain\Entities\Service;
use App\Domain\Contracts\ServiceRepository;
use App\Domain\DTOs\ServiceDTO;

class UpdateService {
  public ServiceRepository $repository;
 
  public function __construct(ServiceRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,ServiceDTO $dto)
  {
    return $this->repository->update($id , $dto);
  }
}