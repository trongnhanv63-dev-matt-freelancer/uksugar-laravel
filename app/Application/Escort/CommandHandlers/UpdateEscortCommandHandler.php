<?php
namespace App\Application\Escort\CommandHandlers;

use App\Application\Escort\Commands\UpdateEscortCommand;
use App\Domain\Escort\Entities\Escort;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class UpdateEscortCommandHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    // Xử lý lệnh cập nhật Escort
    public function handle(UpdateEscortCommand $command): ?Escort
    {
        // Tìm Escort hiện tại
        $escort = $this->escortRepository->findById($command->id);
        if ($escort === null) {
            // Có thể ném exception hoặc xử lý khác nếu không tìm thấy
            return null;
        }
        // Cập nhật thông tin từ DTO
        $data = $command->escortData;
        $escort->setName($data->name);
        $escort->setDescription($data->description);
        $escort->setImage($data->image);
        $escort->setStatus($data->status);
        $escort->setUpdatedBy($data->updated_by);
        // Lưu thay đổi
        return $this->escortRepository->save($escort);
    }
}
