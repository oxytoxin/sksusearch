<div>
    <div class="flex justify-start w-full mb-4 space-x-2">
        <x-filament-support::button icon="heroicon-s-arrow-left" type="button" onclick="window.history.back()">
            Back</x-filament-support::button>
        <button onclick="printDiv('printableDiv')" class="px-4 py-2 bg-primary-500 text-white rounded text-sm">
            Print Document
        </button>
    </div>

    <div class="flex mx-auto w-full justify-center">
        <div class="document bg-white">

            <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
                <x-sksu-header />


                <div class="mb-4 text-gray-800 my-6">
                    <h1 class="text-xl font-bold   text-center ">
                        Formal Management Reminder
                    </h1>
                    <p class="text-center">No. {{ $record?->cash_advance_reminder?->fmr_number ?? '' }}</p>
                </div>

                <div class="border-b pb-2 border-black text-gray-800 text-xs">
                    <div class="flex justify-start font-bold">
                        <p class="label min-w-12">To:</p>
                        <div class="">{{ $record?->user?->name }}</div>
                    </div>
                    <div class="flex justify-start font-bold">
                        <p class="label min-w-12">Re:</p>
                        <div class="">Reminder to liquidate cash advance</div>
                    </div>
                    <div class="flex justify-start font-bold">
                        <p class="label min-w-12">Date:</p>
                        <div class="">
                            {{ $record?->cash_advance_reminder?->fmr_date ? \Carbon\Carbon::parse($record->cash_advance_reminder->fmr_date)->format('F d, Y') : '' }}
                        </div>
                    </div>
                </div>
                {{-- @dump($record) --}}

                <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                    <p>
                        Pursuant to Section 1 of the Sanctions for Violations of Rules and Regulations Related to the
                        Liquidation of
                        Cash Advances, as adopted through BOR Resolution No. 56, s. {{ now()->format('Y') }}, your
                        attention is hereby drawn to the
                        following cash advance that is now due for liquidation:
                    </p>
                </div>

                <x-disbursement-voucher-table :record="$record" />

                <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                    <h1>Purpose:</h1>
                    <ul class="list-disc pl-6 mt-2">
                        @foreach ($record->disbursement_voucher_particulars as $particular)
                            <li>{{ $particular->purpose }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-4 text-xs text-gray-800 leading-relaxed">
                        <p>Please be informed that cash advances, depending on the type, must be liquidated by the
                            following deadlines:</p>
                        <ul class="pl-6 mt-2">
                            <li>(1) Salaries, wages, etc.:<span class="ml-6"> within five (5) after each
                                    15-day/month-end pay period</span><sup>1</sup></li>
                            <li>(2) Local travel: <span class="ml-6">within thirty (30) days after return to permanent
                                    official station</span><sup>2</sup></li>
                            <li>(3) Foreign travel: <span class="ml-6">within sixty (60) days after return to the
                                    Philippines</span><sup>3</sup></li>
                            <li>(4) Special activities: <span class="ml-6">within twenty (20) days from accomplishment
                                    of the purpose</span><sup>4</sup></li>
                        </ul>

                        <p class="mt-4">
                            Immediate liquidation of the aforementioned cash advance is therefore directed. If partial
                            or full
                            liquidation has already been made, please coordinate with the Accounting Office for
                            validation and correction
                            of records.
                        </p>

                        <p class="mt-6">For your guidance and immediate compliance.</p>
                    </div>

                    <div>

                        <div class="mt-6">

                        </div>
                        <x-signature-block :name="App\Models\EmployeeInformation::accountantUser()->full_name" :position="App\Models\EmployeeInformation::accountantUser()?->position->description .
                            ' - ' .
                            App\Models\EmployeeInformation::accountantUser()?->office->name" :signature="App\Models\EmployeeInformation::accountantUser()->user->signature?->content" />
                    </div>
                    <div class="mt-6">
                        <div class=" text-xs text-gray-800">

                            <p><sup>1</sup> Section 5.1.1, COA Circular No. 97-002 dated February 10, 1997</p>
                            <p><sup>2</sup> Section 5.1.3, Ibid.</p>
                            <p><sup>3</sup> Ibid.</p>
                            <p><sup>4</sup> Section 1, COA Circular No. 2012-004 dated November 28, 2012</p>
                        </div>
                    </div>
                </div> <!-- Closing for document div -->
            </div> <!-- Closing for center alignment div -->
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
</div> <!-- Closing for main div -->
