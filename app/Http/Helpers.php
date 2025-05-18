<?php

namespace App\Http;

use DateTime;
use Illuminate\Support\Carbon;

class Helpers
{
    public static function getDateWithTimezone(?DateTime $date)
    {
        if ($date && !empty(env('TIME_ZONE'))) {

            return Carbon::createFromTimestamp($date->getTimestamp())->setTimezone(env('TIME_ZONE'));
        }

        return $date;
    }
}
