<?php
namespace App\Application\Escort\Actions;

use App\Application\Escort\DTOs\EscortData;

class CreateEscortAction
{
    public EscortData $escortData;

    public function __construct(EscortData $escortData)
    {
        $this->escortData = $escortData;
    }
}
