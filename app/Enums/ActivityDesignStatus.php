<?php

namespace App\Enums;

enum ActivityDesignStatus: int
{
    case DRAFT = 0;
    case IN_APPROVAL = 1;
    case RETURNED = 2;
    case REJECTED = 3;
    case APPROVED = 10;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::IN_APPROVAL => 'For Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::RETURNED => 'Returned',
        };
    }
}
