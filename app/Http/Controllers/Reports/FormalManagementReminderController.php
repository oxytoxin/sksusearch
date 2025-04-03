<?php

namespace App\Http\Controllers\Reports;

use App\Models\DisbursementVoucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormalManagementReminderController extends Controller
{
    public function show(DisbursementVoucher $record)
    {
        return view('reports.formal-management-reminder', ['record' => $record]);
    }
}
