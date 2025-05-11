<?php

namespace App\Domain\Escort\ValueObjects;

enum EscortStatus: string
{
    case Public = 'public';
    case Private = 'private';
    case Hidden = 'hidden';
}
