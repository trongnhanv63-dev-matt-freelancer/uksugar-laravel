<?php

namespace App\Application\Escort\QueryHandlers;

use App\Application\Escort\Queries\ListEscortsQuery;
use App\Application\Escort\DTOs\EscortData;
use App\Domain\Escort\Repositories\EscortRepositoryInterface;

class ListEscortsQueryHandler
{
    private EscortRepositoryInterface $escortRepository;

    public function __construct(EscortRepositoryInterface $escortRepository)
    {
        $this->escortRepository = $escortRepository;
    }

    // Xử lý truy vấn liệt kê Escort có phân trang
    public function handle(ListEscortsQuery $query): array
    {
        $results = $this->escortRepository->paginate($query->perPage, $query->page);
        $dtos = [];
        foreach ($results as $escort) {
            $dtos[] = new EscortData(
                $escort->getName(),
                $escort->getDescription(),
                $escort->getImage(),
                $escort->getStatus(),
                $escort->getCreatedBy(),
                $escort->getUpdatedBy()
            );
        }
        return $dtos;
    }
}
