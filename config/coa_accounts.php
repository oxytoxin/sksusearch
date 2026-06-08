<?php

/*
|--------------------------------------------------------------------------
| COA Cash-Advance GL Account Mapping
|--------------------------------------------------------------------------
|
| The Cash Advance Aging report needs the COA general-ledger account
| (e.g. "1911000 · Advances for Operating Expenses") per cash advance.
| SEARCH does not store the GL code on the voucher, so it is DERIVED from
| the voucher subtype here. Accounting can adjust these mappings without
| touching code.
|
| Codes follow the NGAS 7-digit chart the client uses in their COA aging
| schedule (191x000 Advances series).
|
*/

return [

    // voucher_sub_type_id => ['code' => ..., 'name' => ...]
    'subtype_accounts' => [
        1   => ['code' => '1914000', 'name' => 'Advances to Officers and Employees'], // Local Travel
        2   => ['code' => '1914000', 'name' => 'Advances to Officers and Employees'], // Foreign Travel
        3   => ['code' => '1911000', 'name' => 'Advances for Operating Expenses'],    // Activity, Program, Project
        4   => ['code' => '1912000', 'name' => 'Advances for Payroll'],               // Payroll
        5   => ['code' => '1913000', 'name' => 'Advances to Special Disbursing Officer'], // Special Disbursing Officer
        97  => ['code' => '1914000', 'name' => 'Advances to Officers and Employees'], // Local Travel (Legacy)
        98  => ['code' => '1914000', 'name' => 'Advances to Officers and Employees'], // Foreign Travel (Legacy)
        103 => ['code' => '1914000', 'name' => 'Advances to Officers and Employees'], // Travel involving students
        77  => ['code' => '1911000', 'name' => 'Advances for Operating Expenses'],    // Legacy Cash Advances (default)
    ],

    // Used when a subtype is not listed above.
    'default' => ['code' => '1911000', 'name' => 'Advances for Operating Expenses'],
];
