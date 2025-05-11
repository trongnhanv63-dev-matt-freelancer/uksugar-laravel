<?php
namespace App\Application\Escort\Actions;

class DeleteEscortAction
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
