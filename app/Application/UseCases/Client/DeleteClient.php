<?php

namespace App\Application\UseCases\Client;

use App\Domain\Contracts\ClientRepository;

class DeleteClient {
    public ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }
}