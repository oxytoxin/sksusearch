<div>
    <div class="flex justify-start w-full mb-4 space-x-2 ">
          <x-filament-support::button icon="heroicon-s-arrow-left" type="button" onclick="window.history.back()" >   Back</x-filament-support::button>

        <button onclick="printDiv('printableDiv')" class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
            Print Document
        </button>
    </div>

    <div class="flex mx-auto w-full justify-center">
        <div class="document bg-white">


            <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
                <x-sksu-header />

                <h1 class="text-xl font-bold  pt-1 mt-2 text-center">
                    Office of the President
                </h1>
                <h1 class="    text-center mt-2 mb-8">
                    ENDORSEMENT FOR ISSUANCE OF FORMAL DEMAND TO LIQUIDATE
                </h1>
                <div class="text-xs text-gray-800">
                    <p class="">
                        {{ $record?->cash_advance_reminder?->fd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fd_date)->format('F d, Y') : '' }}
                    </p>
                    <p class="mt-4 font-bold">{{ App\Models\EmployeeInformation::auditorUser()->full_name }}</p>
                    <p>{{ App\Models\EmployeeInformation::auditorUser()?->position->description }}</p>
                    <p>{{ App\Models\EmployeeInformation::auditorUser()?->office->name }}</p>
                    <p class="mt-2">Sir/Madame:</p>

                    <p class="mt-4">
                        This is in relation to the following cash advance issued to <span
                            class="underline">{{ $record?->user?->name }}</span>:
                    </p>

                    <x-disbursement-voucher-table :record="$record" />
                    <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                        <p>
                            As of this writing, the cash advance has been outstanding for
                            {{ $record->daysOutstanding() }} days from the end of the travel/implementation/payroll
                            period.
                        </p>

                        <p class="mt-4">
                            Management had already issued the following notices:
                        </p>
                        <ul class="list-decimal pl-6 mt-2">
                            <li>Formal Management Reminder, FMR No.
                                {{ $record?->cash_advance_reminder?->fmr_number ?? '' }} dated
                                {{ $record?->cash_advance_reminder?->fmr_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmr_date)->format('F d, Y') : '' }}
                            </li>
                            <li>Formal Management Demand, FMD No. {{ $record?->cash_advance_reminder->fmd_number ?? '' }}
                                dated
                                {{ $record?->cash_advance_reminder?->fmd_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmd_date)->format('F d, Y') : '' }}
                            </li>
                            <li>Show Cause Order, Office of the President Memo No.
                                {{ $record->cash_advance_reminder->memorandum_number ?? '' }}, s.
                                {{ $record?->cash_advance_reminder?->sco_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->sco_date)->format('Y') : 'xx' }}
                                dated
                                {{ $record?->cash_advance_reminder?->sco_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->sco_date)->format('F d, Y') : '' }}
                            </li>
                        </ul>

                        <p class="mt-4">
                            Despite service of the aforesaid notices containing urgent directives to render account on
                            the cash advance, the
                            accountable officer still failed to make substantial compliance with the given orders.
                            Management already sees
                            this as constituting wilful negligence on his/her part with respect to a reasonable official
                            order and an outright
                            refusal by him/her to perform an obligation required by law.
                        </p>

                        <p class="mt-4">
                            In view of the foregoing premises, request is hereby made for the issuance of a <b>FORMAL
                                DEMAND</b> as provided for under Section 5 of CSC Memorandum Circular No. 23, s. 2019.
                        </p>

                        <p class="mt-4">
                            We will appreciate the transmittal of the requested document within three (3) working days
                            from receipt of this
                            endorsement.
                        </p>

                        <p class="mt-4">We look forward to your usual support.</p>

                        <div class="mt-12"></div>
                        <div class="relative mt-6">

                             <img src="{{ App\Models\EmployeeInformation::presidentUser()->user->signature?->content }}"
                                    alt="" class="absolute  h-24 w-24 inset-x-1 bottom-[-1rem]">

                            <p class="mt-6 font-bold">{{ App\Models\EmployeeInformation::presidentUser()->full_name }}</p>
                        </div>

                        <p>University President</p>
                    </div>
                </div>
            </div>

        </div>
        <livewire:requisitioner.message-reply-section :disbursement_voucher="$record" />
    </div>


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
