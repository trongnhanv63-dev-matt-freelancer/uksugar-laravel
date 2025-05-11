<?php
namespace App\Application\Escort\CommandHandlers;

use App\Application\Escort\Commands\DeleteEscortCommand;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class DeleteEscortCommandHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    // Xử lý lệnh xóa Escort
    public function handle(DeleteEscortCommand $command): void
    {
        $escort = $this->escortRepository->findById($command->id);
        if ($escort !== null) {
            $this->escortRepository->delete($escort);
        }
        // Nếu không tìm thấy, có thể ném exception hoặc bỏ qua
    }
}
