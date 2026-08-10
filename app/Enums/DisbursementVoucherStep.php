<?php

    namespace App\Enums;

    enum DisbursementVoucherStep: int
    {
        case FORWARD_REQUISITIONER = 1000;
        case RECEIVED_REQUISITIONER = 2000;
        case FORWARD_SIGNATORY = 3000;
        case RECEIVED_SIGNATORY = 4000;
        case FORWARD_ICU = 5000;
        case RECEIVED_ICU = 6000;
        case FORWARD_BUDGET = 7000;
        case RECEIVED_BUDGET = 8000;
        case BUDGET_ORS_BURS = 9000;
        case FORWARD_ACCOUNTING = 10000;
        case RECEIVED_ACCOUNTING = 11000;
        case VERIFY_ACCOUNTING = 12000;
        case FORWARD_PRESIDENT = 13000;
        case RECEIVED_PRESIDENT = 14000;
        case FORWARD_CASHIER = 15000;
        case RECEIVED_CASHIER = 16000;
        case CHEQUE_ISSUED = 17000;
        case FORWARD_POST_ICU = 18000;
        case RECEIVED_POST_ICU = 19000;
        case FORWARD_ARCHIVER = 20000;
        case RECEIVED_ARCHIVER = 21000;
        case DOCUMENT_ARCHIVED = 22000;
        case FORWARD_COA = 23000;
        case RECEIVED_COA = 24000;

    }
