<?php

namespace App\Infrastructure\Escort\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Domain\Escort\ValueObjects\EscortStatus;

class EscortModel extends Model
{
    protected $table = 'escorts';

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => EscortStatus::class,
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
