<?php

namespace App\Enums;

enum ActivityDesignSignatoryGroupStatus: int
{
    case DRAFT = 0;
    case IN_APPROVAL = 1;
    case WAITING = 2;
    case APPROVED = 10;

    public function description()
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::IN_APPROVAL => 'On-going approval',
            self::WAITING => 'Waiting for previous group',
            self::APPROVED => 'Approved',
        };
    }
}
