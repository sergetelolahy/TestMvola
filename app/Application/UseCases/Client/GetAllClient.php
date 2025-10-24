<?php 

namespace App\Application\UseCases\Client;

use App\Domain\Contracts\ClientRepository;

use App\Domain\DTOs\Client\ClientOutputDTO;


class GetAllClient {
    public ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
      $this->repository = $repository;
    }

    public function execute(): array
    {
      $entities = $this->repository->getAll();
      return array_map(fn($e) => new ClientOutputDTO($e->id,$e->nom,$e->prenom,$e->tel,$e->email,$e->cin),$entities);
    }
}