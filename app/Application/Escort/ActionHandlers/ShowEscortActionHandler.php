<?php

namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\ShowEscortAction;
use App\Application\Escort\DTOs\EscortData;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class ShowEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(ShowEscortAction $action): ?EscortData
    {
        $escort = $this->escortRepository->findById($action->id);
        if ($escort === null) {
            return null;
        }
        return new EscortData(
            $escort->getName(),
            $escort->getDescription(),
            $escort->getImage(),
            $escort->getStatus(),
            $escort->getCreatedBy(),
            $escort->getUpdatedBy()
        );
    }
}
