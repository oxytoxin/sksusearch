<?php

namespace App\Enums;

enum ActivityDesignStatus: int
{
    case DRAFT = 0;
    case IN_APPROVAL = 1;
    case APPROVED = 10;
}
