<?php

namespace App\Enums;

/**
 * A reusable enum for active/inactive status across different models.
 */
enum StatusEnum: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
