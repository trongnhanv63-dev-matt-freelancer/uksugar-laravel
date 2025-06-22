<?php

namespace App\Enums;

enum UserStatus: string
{
    case PendingVerification = 'pending_verification';
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Banned = 'banned';
    case Locked = 'locked';
}
