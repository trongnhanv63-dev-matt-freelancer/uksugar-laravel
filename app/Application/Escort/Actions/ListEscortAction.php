<?php
namespace App\Application\Escort\Actions;

class ListEscortAction
{
    public int $perPage;
    public int $page;

    public function __construct(int $perPage = 10, int $page = 1)
    {
        $this->perPage = $perPage;
        $this->page = $page;
    }
}
