<?php

namespace App\Application\UseCases\Client;

use App\Domain\Contracts\ClientRepository;
use App\Domain\DTOs\Client\ClientInputDTO;


class UpdateClient {
  public ClientRepository $repository;
 
  public function __construct(ClientRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(int $id,ClientInputDTO $dto)
  {
    return $this->repository->update($id , $dto);
  }
}