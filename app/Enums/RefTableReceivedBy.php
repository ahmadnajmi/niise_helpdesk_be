<?php

namespace App\Enums;

enum RefTableReceivedBy: int
{
    case EMAIL = 1;
    case LIVECHAT = 2;

    public function label(): string
    {
        return match($this) {
            self::EMAIL => 'Email',
            self::LIVECHAT => 'Livechat',
        };
    }
}
