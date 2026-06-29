<?php

    use App\Http\Controllers\AttachmentsController;
    use App\Http\Controllers\GenerateWfpPpmpExportController;
    use App\Models\DisbursementVoucher;
    use Carbon\Carbon;
    use App\Models\CaReminderStep;
    use App\Models\LegacyDocument;
    use App\Models\FuelRequisition;
    use App\Http\Livewire\TestComponent;
    use Illuminate\Support\Facades\Route;
    use App\Http\Livewire\Test\CountetTest;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Livewire\Shared\TravelCompletedCertificatePrint;
use App\Models\FundAllocation;
use Illuminate\Support\Facades\DB;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::middleware('auth')->group(function () {
        Route::redirect('/', '/requisitioner/dashboard');
        Route::get('/disbursement-voucher-view/{disbursement_voucher}', [HomeController::class, 'disbursement_voucher_view'])->name('disbursement-vouchers.show');
        Route::get('/disbursement-voucher-view-no-layout/{disbursement_voucher}', [HomeController::class, 'disbursement_voucher_view_no_layout'])->name('disbursement-vouchers-no-layout.show');
        Route::get('/certification-of-travel-completion/{ctc}', TravelCompletedCertificatePrint::class)->name('ctc.show');
        Route::get('/sms-details', App\Http\Livewire\SmsDetails::class)->name('sms-details');
    });
    Route::get('/disbursement-voucher-view/tracking/{disbursement_voucher:tracking_number}', [HomeController::class, 'disbursement_voucher_view'])->name('disbursement-vouchers.show-from-trn');
    Route::get('auth/google', 'App\Http\Controllers\GoogleController@redirectToGoogle');
    Route::get('auth/google/callback', 'App\Http\Controllers\GoogleController@handleGoogleCallback');
    Route::get('no-account', fn () => view('auth.no-account'))->name('401-error');
    Route::get('download/employee-template', fn () => response()->download(storage_path('app/employee_template.xlsx')))->name('download.employee-template');
    Route::middleware(['auth:sanctum', 'verified'])->get('redirects', 'App\Http\Controllers\HomeController@index')->name('redirect');

    Route::get('/test', function () {
        $fuel = FuelRequisition::first();
        return view('components.motorpool.fuel-requisition-slip', [
            'fuel_request' => $fuel
        ]);
    });

    // Preview the Pre-Audit Notice email in-browser (renders only, never sends).
    Route::get('/preview/pre-audit-notice/{disbursementVoucher}', function (DisbursementVoucher $disbursementVoucher) {
        return (new \App\Mail\PreAuditNoticeMail($disbursementVoucher))->render();
    })->middleware('auth')->name('preview.pre-audit-notice');

    // Email health-check: hit after deploy to confirm the mail provider works.
    // Sends a real test email and returns the active config + send result as JSON.
    // Recipient: ?to=someone@example.com, else the logged-in user's email.
    Route::get('/email/test', function (\Illuminate\Http\Request $request) {
        $to = $request->query('to', optional($request->user())->email);

        if (! $to) {
            return response()->json([
                'ok' => false,
                'error' => 'No recipient. Pass ?to=email@example.com or log in with an email on your account.',
            ], 422);
        }

        $result = app(\App\Services\EmailService::class)->sendEmail(
            $to,
            'SEARCH email test',
            'Email Test',
            'If you received this, the SEARCH email channel is working. Sent at ' . now()->toDateTimeString() . '.'
        );

        return response()->json([
            'ok'      => $result['success'],
            'mailer'  => config('mail.default'),
            'host'    => config('mail.mailers.smtp.host'),
            'from'    => config('mail.from.address'),
            'to'      => $to,
            'error'   => $result['error'] ?? null,
            'sent_at' => now()->toDateTimeString(),
        ], $result['success'] ? 200 : 500);
    })->middleware('auth')->name('email.test');

    Route::get('/export/cost-center', App\Http\Controllers\TestController::class)->name('test.pre');


    Route::get('/attachments/{attachment}/download', [AttachmentsController::class, 'download'])->name('attachments.download');


    Route::get('/reports/generate-wfp-ppmp', [GenerateWfpPpmpExportController::class, 'index'])->name('generate-wfp-ppmp-report');

    Route::get('/test-example', function () {
        $now = Carbon::now();
        // $voucher  = CaReminderStep::find(6);
        $voucher = CaReminderStep::find(1);
        // dd($voucher->disbursement_voucher);


        NotificationController::sendCASystemReminder('Type', 'Title', 'Mesage', 'Sender Name', auth()->user()->name, auth()->user()->id, auth()->user(), 'facebook.com', $voucher->disbursement_voucher);


        // $cashAdvances = CaReminderStep::whereHas('disbursement_voucher.liquidation_report',function($query){
        //      $query->where('current_step_id','!=', 8000);
        // })
        //  ->where('status','On-Going')
        // ->get();


        // dd($cashAdvances);
    });

    Route::get('/blade-view', function () {
        return view('reports.endorsement-for-f-d', [
            'record' => DisbursementVoucher::first()
        ]);
    });


   Route::get('/fund-allocation-batch', function () {
        $data =  FundAllocation::query()
    ->fromSub(function ($query) {
        $query->from('fund_allocations')
            ->selectRaw('
                *,
                ROW_NUMBER() OVER (
                    PARTITION BY
                        cost_center_id,
                        supplemental_quarter_id,
                        fund_cluster_id,
                        wpf_type_id
                    ORDER BY id ASC
                ) as rn
            ');
    }, 't')
    ->where('rn', 1)
    ->get();
    return response()->json($data);
    });
