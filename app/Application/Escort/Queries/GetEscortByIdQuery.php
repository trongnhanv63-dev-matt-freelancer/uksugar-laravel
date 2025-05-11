<?php
namespace App\Application\Escort\Queries;

// Truy vấn lấy Escort theo ID
class GetEscortByIdQuery
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
