<?php

namespace App\Http\Controllers;

use App\Models\DisbursementVoucher;
use App\Models\TravelCompletedCertificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('requisitioner.dashboard');
    }

    public function disbursement_voucher_view(DisbursementVoucher $disbursement_voucher)
    {
        return view('components.disbursement_vouchers.disbursement_voucher_view', [
            'disbursement_voucher' => $disbursement_voucher
        ]);
    }
}
