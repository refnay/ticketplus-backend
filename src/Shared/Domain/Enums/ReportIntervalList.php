<?php

namespace App\Shared\Domain\Enums;

enum ReportIntervalList: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
}
