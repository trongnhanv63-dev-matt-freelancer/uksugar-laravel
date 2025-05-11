<?php

namespace App\Infrastructure\Escort\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Domain\Escort\ValueObjects\EscortStatus;

class EscortModel extends Model
{
    // Đặt tên bảng tương ứng
    protected $table = 'escorts';

    // Các trường có thể gán giá trị hàng loạt
    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];

    // Casts cho các trường
    protected $casts = [
        // Sử dụng PHP Enum cho trường status
        'status' => EscortStatus::class,
        // Nêu rõ kiểu int cho created_by, updated_by nếu cần
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Thiết lập quan hệ thuộc về (belongsTo) tới User:
     * - user tạo escort
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Quan hệ belongsTo với User:
     * - user cập nhật escort
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
