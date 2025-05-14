<?php

namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\ListEscortAction;
use App\Application\Escort\DTOs\EscortData;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class ListEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(ListEscortAction $action): array
    {
        $results = $this->escortRepository->paginate($action->perPage, $action->page);
        return array_map(function($entity){
            return $entity->toArray();
        }, $results);
    }
}
