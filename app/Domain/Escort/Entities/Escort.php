<?php
namespace App\Domain\Escort\Entities;

use App\Domain\Escort\ValueObjects\EscortStatus;

/**
 * Lớp đại diện cho thực thể Escort trong miền (Domain).
 * Bao gồm các thuộc tính và phương thức getter/setter để quản lý dữ liệu.
 */
class Escort
{
    /**
     * ID của Escort (ví dụ kiểu int hoặc chuỗi UUID).
     *
     * @var int
     */
    private $id;

    /**
     * Tên của Escort.
     *
     * @var string
     */
    private $name;

    /**
     * Mô tả về Escort.
     *
     * @var string
     */
    private ?string $description;

    /**
     * Đường dẫn hoặc tên file ảnh đại diện của Escort.
     *
     * @var string
     */
    private ?string $image;

    /**
     * Trạng thái của Escort, sử dụng enum EscortStatus để đảm bảo giá trị hợp lệ.
     *
     * @var EscortStatus
     */
    private ?EscortStatus $status;

    /**
     * Người tạo Escort (có thể là ID người dùng hoặc tên người dùng, tùy thiết kế).
     *
     * @var int
     */
    private ?int $createdBy;

    /**
     * Người cập nhật cuối (có thể là ID người dùng hoặc tên người dùng).
     *
     * @var int
     */
    private ?int $updatedBy;

    /**
     * Constructor khởi tạo đầy đủ thuộc tính cho Escort.
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $image
     * @param EscortStatus $status
     * @param int $createdBy
     * @param int $updatedBy
     */
    public function __construct(?int $id = null, string $name, string $description, string $image, EscortStatus $status, int $createdBy, int $updatedBy)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Thiết lập ID của Escort.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Thiết lập tên của Escort.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Thiết lập mô tả của Escort.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Thiết lập ảnh đại diện của Escort.
     *
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * Thiết lập trạng thái của Escort.
     * Chỉ nhận giá trị thuộc enum EscortStatus để đảm bảo hợp lệ.
     *
     * @param EscortStatus $status
     */
    public function setStatus(EscortStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * Thiết lập người tạo của Escort.
     *
     * @param int $createdBy
     */
    public function setCreatedBy(int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Thiết lập người cập nhật cuối của Escort.
     *
     * @param int $updatedBy
     */
    public function setUpdatedBy(int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * Lấy ID của Escort.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Lấy tên của Escort.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Lấy mô tả của Escort.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Lấy ảnh đại diện của Escort.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Lấy trạng thái của Escort.
     *
     * @return EscortStatus
     */
    public function getStatus(): EscortStatus
    {
        return $this->status;
    }

    /**
     * Lấy ID người tạo Escort.
     *
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    /**
     * Lấy ID người cập nhật cuối Escort.
     *
     * @return int
     */
    public function getUpdatedBy(): int
    {
        return $this->updatedBy;
    }
}
