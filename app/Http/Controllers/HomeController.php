<?php

namespace App\Http\Controllers;

use App\Models\DisbursementVoucher;
use App\Models\Mop;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('requisitioner.dashboard');
    }

    public function dashboard()
    {
        if (auth()->user()->employee_information->contact_number == null) {
            return redirect()->route('requisitioner.contact-number');
        }
        if (! auth()->user()->signature()->exists()) {
            return redirect()->route('requisitioner.signature');
        }

        return view('dashboard');
    }

    public function disbursement_voucher_view(DisbursementVoucher $disbursement_voucher)
    {
        return view('components.disbursement_vouchers.disbursement_voucher_view', [
            'disbursement_voucher' => $disbursement_voucher->load([
                'signatoryApproval.user.signature',
                'signatoryApproval.user.employee_information.position',
                'signatoryApproval.user.employee_information.office',
                'signatoryApproval.oic.signature',
                'signatoryApproval.oic.employee_information.position',
                'signatoryApproval.oic.employee_information.office',
                'signatory.signature',
                'signatory.employee_information.position',
                'signatory.employee_information.office',
            ]),
            'mops' => Mop::all(),
        ]);
    }

    public function disbursement_voucher_view_no_layout(DisbursementVoucher $disbursement_voucher)
    {
        return view('components.disbursement_vouchers.disbursement_voucher_view_no_layout', [
            'disbursement_voucher' => $disbursement_voucher,
        ]);
    }
}
