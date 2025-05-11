<?php

namespace App\Application\Escort\CommandHandlers;

use App\Application\Escort\Commands\CreateEscortCommand;
use App\Domain\Escort\Entities\Escort;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class CreateEscortCommandHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    // Xử lý lệnh tạo Escort
    public function handle(CreateEscortCommand $command): Escort
    {
        $data = $command->escortData;
        // Tạo entity Escort (giả sử Domain\Entity có constructor phù hợp)
        $escort = new Escort(
            null,
            $data->name,
            $data->description,
            $data->image,
            $data->status,
            $data->created_by,
            $data->updated_by
        );
        // Lưu và trả về Escort đã lưu
        return $this->escortRepository->save($escort);
    }
}
