<?php

namespace App\Application\UseCases\Service;

use App\Domain\Contracts\ServiceRepository;

class DeleteService {
    public ServiceRepository $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id) : bool
    {
        return $this->repository->delete($id);
    }
}