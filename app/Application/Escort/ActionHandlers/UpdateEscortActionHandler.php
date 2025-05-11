<?php
namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\UpdateEscortAction;
use App\Domain\Escort\Entities\Escort;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class UpdateEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(UpdateEscortAction $action): ?Escort
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
        return $this->escortRepository->save($escort);
    }
}
