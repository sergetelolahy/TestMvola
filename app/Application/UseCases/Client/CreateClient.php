<?php 

namespace App\Application\UseCases\Client;

use App\Domain\Contracts\ClientRepository;
use App\Domain\DTOs\Client\ClientInputDTO;

class CreateClient {
  public ClientRepository $repository;

  public function __construct(ClientRepository $repository)
  {
    $this->repository = $repository;
  }

  public function execute(ClientInputDTO $dto){
    return $this->repository->save($dto);
  }
}