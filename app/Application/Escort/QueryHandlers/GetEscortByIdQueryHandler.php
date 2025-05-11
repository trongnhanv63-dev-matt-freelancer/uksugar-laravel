<?php

namespace App\Application\Escort\QueryHandlers;

use App\Application\Escort\Queries\GetEscortByIdQuery;
use App\Application\Escort\DTOs\EscortData;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class GetEscortByIdQueryHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    // Xử lý truy vấn lấy Escort theo ID
    public function handle(GetEscortByIdQuery $query): ?EscortData
    {
        $escort = $this->escortRepository->findById($query->id);
        if ($escort === null) {
            return null;
        }
        // Chuyển đổi từ Entity Escort sang DTO EscortData
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
