<?php

namespace App\Domain\Escort\Entities;

use App\Domain\Escort\ValueObjects\EscortStatus;

class EscortEntity
{

    private $id;
    private $name;
    private $description;
    private $image;
    private $status;
    private $createdBy;
    private $updatedBy;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        ?int $id,
        string $name,
        ?string $description = null,
        ?string $image = null,
        ?EscortStatus $status = EscortStatus::Public,
        ?int $createdBy = null,
        ?int $updatedBy = null,
        ?\DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray() : array{
        return get_object_vars($this);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getStatus(): EscortStatus
    {
        return $this->status;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
