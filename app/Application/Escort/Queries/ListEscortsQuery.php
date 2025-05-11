<?php
namespace App\Application\Escort\Queries;

// Truy vấn liệt kê Escort theo trang
class ListEscortsQuery
{
    public int $perPage;
    public int $page;

    public function __construct(int $perPage = 10, int $page = 1)
    {
        $this->perPage = $perPage;
        $this->page = $page;
    }
}
