<?php
namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\UpdateEscortAction;
use App\Domain\Escort\Entities\EscortEntity;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class UpdateEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(UpdateEscortAction $action): ?EscortEntity
    {
        $escort = $this->escortRepository->findById($action->id);
        if ($escort === null) {
            return null;
        }
        $data = $action->escortData;
        $escort->setName($data->name);
        $escort->setDescription($data->description);
        $escort->setImage($data->image);
        $escort->setStatus($data->status);
        $escort->setUpdatedBy($data->updated_by);
        $escort->setUpdatedAt($data->updated_at);
        return $this->escortRepository->save($escort);
    }
}
