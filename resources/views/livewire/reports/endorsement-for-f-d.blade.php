<div>
    <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
        <x-sksu-header />

        <h1 class="text-xl font-bold  pt-1 mt-2 text-center">
            Office of the President
        </h1>
        <h1 class="    text-center my-6">
            ENDORSEMENT FOR ISSUANCE OF FORMAL DEMAND TO LIQUIDATE
        </h1>
        <div class="text-xs text-gray-800">
            <p class="">{{ $record?->cash_advance_reminder?->fd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fd_date)->format('F d, Y') : '' }}</p>
            <p class="mt-4 font-bold">{{ App\Models\EmployeeInformation::auditorUser()->full_name }}</p>
            <p>{{App\Models\EmployeeInformation::auditorUser()?->position->description}}</p>
            <p>{{App\Models\EmployeeInformation::auditorUser()?->office->name}}</p>
            <p class="mt-2">Sir/Madame:</p>

            <p class="mt-4">
                This is in relation to the following cash advance issued to <span class="underline">{{$record?->user?->name}}</span>:
            </p>

            <div class="text-xs text-gray-900 mt-2">
                <table class="w-full">
                    <tr>
                        <td class="border border-gray-800 px-2">DV number:</td>
                        <td class="border border-gray-800 px-2">{{$record->dv_number ??''}}</td>
                        <td class="border border-gray-800 px-2">End of travel/implementation/payroll period:</td>
                        <td class="border border-gray-800 px-2">{{ $record?->cash_advance_reminder?->voucher_end_date ? date_format(date_create($record->cash_advance_reminder->voucher_end_date), 'F d, Y') : '' }} </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-800 px-2">Amount:</td>
                        <td class="border border-gray-800 px-2">{{ number_format($record->totalSumDisbursementVoucherParticular() ?? 0, 2) }}</td>
                        <td class="border border-gray-800 px-2">Liquidation deadline:</td>
                        <td class="border border-gray-800 px-2">{{ $record?->cash_advance_reminder?->liquidation_period_end_date ? date_format(date_create($record->cash_advance_reminder->liquidation_period_end_date), 'F d, Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-800 px-2">Check/ADA number</td>
                        <td class="border border-gray-800 px-2">{{$record->cheque_number ??''}}</td>
                        <td class="border border-gray-800 px-2">Date Disbursed</td>
                        <td class="border border-gray-800 px-2">
                            {{ $record?->cheque_number_added_at ? date_format(date_create($record->cheque_number_added_at), 'F d, Y') : '' }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="mt-4 text-xs text-gray-800 leading-relaxed">
            <p>
                As of this writing, the cash advance has been outstanding for <span class="underline">xx</span> days from the end of the travel/implementation/payroll period.
            </p>

            <p class="mt-4">
                Management had already issued the following notices:
            </p>
            <ul class="list-decimal pl-6 mt-2">
                <li>Formal Management Reminder, FMR No.  {{ $record?->cash_advance_reminder?->fmr_number ??'' }} dated {{ $record?->cash_advance_reminder?->fmr_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmr_date)->format('F d, Y') : '' }}, received {{ $record?->cash_advance_reminder?->fmr_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmr_date)->format('F d, Y') : '' }}</li>
                <li>Formal Management Demand, FMD No. {{ $record?->cash_advance_reminder->fmd_number ??'' }} dated {{ $record?->cash_advance_reminder?->fmd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmd_date)->format('F d, Y') : '' }}, received {{ $record?->cash_advance_reminder?->fmd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmd_date)->format('F d, Y') : '' }}</li>
                <li>Show Cause Order, Office of the President Memo No. {{ $record->cash_advance_reminder->memorandum_number }}, s.  xx dated {{ $record?->cash_advance_reminder?->sco_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->sco)->format('F d, Y') : '' }}, received {{ $record?->cash_advance_reminder?->sco ? \Carbon\Carbon::parse($record->cash_advance_reminder->sco)->format('F d, Y') : '' }}</li>
            </ul>

            <p class="mt-4">
                Despite service of the aforesaid notices containing urgent directives to render account on the cash advance, the
accountable officer still failed to make substantial compliance with the given orders. Management already sees
this as constituting wilful negligence on his/her part with respect to a reasonable official order and an outright
refusal by him/her to perform an obligation required by law.
            </p>

            <p class="mt-4">
                In view of the foregoing premises, request is hereby made for the issuance of a <b>FORMAL DEMAND</b> as provided for under Section 5 of CSC Memorandum Circular No. 23, s. 2019.
            </p>

            <p class="mt-4">
            We will appreciate the transmittal of the requested document within three (3) working days from receipt of this
            endorsement.
            </p>

            <p class="mt-4">We look forward to your usual support.</p>

            <p class="mt-6 font-bold">{{App\Models\EmployeeInformation::presidentUser()->full_name}}</p>
            <p>University President</p>
            </div>
        </div>
    </div>

    <button onclick="printDiv('printableDiv')" class="mt-4 px-4 py-2 bg-primary-500 text-white rounded">
        Print Document
    </button>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</div>
