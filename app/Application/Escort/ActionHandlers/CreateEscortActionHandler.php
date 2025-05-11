<?php

namespace App\Application\Escort\ActionHandlers;

use App\Application\Escort\Actions\CreateEscortAction;
use App\Domain\Escort\Entities\EscortEntity;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class CreateEscortActionHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    public function handle(CreateEscortAction $action): EscortEntity
    {
        $data = $action->escortData;
        $escort = new EscortEntity(
            null,
            $data->name,
            $data->description,
            $data->image,
            $data->status,
            $data->created_by,
            $data->updated_by
        );
        return $this->escortRepository->save($escort);
    }
}
