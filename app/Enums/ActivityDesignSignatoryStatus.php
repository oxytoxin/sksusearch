<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ActivityDesignSignatoryStatus: int implements HasLabel
{
    case IN_APPROVAL = 0;
    case APPROVED = 1;
    case RETURNED = 2;
    case REJECTED = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IN_APPROVAL => 'For Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::RETURNED => 'Returned',
        };
    }
}
