<?php

namespace App\Enums;

enum RefTableBranchType: int
{
    case CRITICAL = 1;
    case NON_CRITICAL = 2;

    public function label(): string
    {
        return match($this) {
            self::CRITICAL => 'Critical',
            self::NON_CRITICAL => 'Non Critical',
        };
    }
}
