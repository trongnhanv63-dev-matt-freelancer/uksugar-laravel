<?php

namespace App\Application\Escort\Actions;

use App\Application\Escort\DTOs\EscortData;

// Lệnh cập nhật Escort
class UpdateEscortAction
{
    public int $id;
    public EscortData $escortData;

    public function __construct(int $id, EscortData $escortData)
    {
        $this->id = $id;
        $this->escortData = $escortData;
    }
}
