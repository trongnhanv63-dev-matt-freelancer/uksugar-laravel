<?php
namespace App\Domain\Escort\Entities;

use App\Domain\Escort\ValueObjects\EscortStatus;

/**
 * Lớp đại diện cho thực thể Escort trong miền (Domain).
 * Bao gồm các thuộc tính và phương thức getter/setter để quản lý dữ liệu.
 */
class EscortEntity
{

    private $id;
    private $name;
    private ?string $description;
    private ?string $image;
    private ?EscortStatus $status;
    private ?int $createdBy;
    private ?int $updatedBy;

    public function __construct(?int $id, string $name, ?string $description = null, ?string $image = null, ?EscortStatus $status = EscortStatus::Public, ?int $createdBy = null, ?int $updatedBy = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function setStatus(EscortStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function setUpdatedBy(int $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getStatus(): EscortStatus
    {
        return $this->status;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): int
    {
        return $this->updatedBy;
    }
}
