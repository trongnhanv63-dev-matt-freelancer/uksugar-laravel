<?php
namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\DeleteEscortAction;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class DeleteEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(DeleteEscortAction $Action): void
    {
        $escort = $this->escortRepository->findById($Action->id);
        if ($escort !== null) {
            $this->escortRepository->delete($escort);
        }
    }
}
