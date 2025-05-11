<?php
namespace App\Application\Escort\Commands;

// Lệnh xóa Escort theo ID
class DeleteEscortCommand
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
