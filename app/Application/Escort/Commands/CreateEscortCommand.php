<?php
namespace App\Application\Escort\Commands;

use App\Application\Escort\DTOs\EscortData;

// Lệnh tạo Escort mới
class CreateEscortCommand
{
    public EscortData $escortData;

    public function __construct(EscortData $escortData)
    {
        $this->escortData = $escortData;
    }
}
