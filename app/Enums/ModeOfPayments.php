<?php

    namespace App\Enums;

    enum ModeOfPayments: int
    {
        case MDS_CHECK = 1;
        case COMMERCIAL_CHECK = 2;
        case ADA = 3;

        public static function getCheckPayments(): array
        {
            return [
                self::MDS_CHECK->value,
                self::COMMERCIAL_CHECK->value,
            ];
        }

        public static function getAdaPayments(): array
        {
            return [
                self::ADA->value,
            ];
        }
    }
