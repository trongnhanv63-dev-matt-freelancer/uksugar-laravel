<?php
namespace App\Application\Escort\Actions;

class ShowEscortAction
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
