<?php

namespace App\Application\Escort\DTOs;

use App\Domain\Escort\ValueObjects\EscortStatus;

class EscortData
{
    public string $name;
    public ?string $description;
    public ?string $image;
    public ?EscortStatus $status;
    public ?int $created_by;
    public ?int $updated_by;

    public function __construct(
        string $name,
        ?string $description = null,
        ?string $image = null,
        ?EscortStatus $status = EscortStatus::Public,
        ?int $created_by = null,
        ?int $updated_by = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
