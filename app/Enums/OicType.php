<?php

    namespace App\Enums;

    enum OicType: string
    {
        case OIC = 'oic';
        case DELEGATED_AUTHORITY = 'da';


        public static function getOptions(): array
        {
            return [
                self::OIC->value => 'OIC',
                self::DELEGATED_AUTHORITY->value => 'Delegated Authority',
            ];
        }
    }
