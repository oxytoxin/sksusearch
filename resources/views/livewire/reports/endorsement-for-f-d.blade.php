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
            <p class="">[Date]</p>
            <p class="mt-4 font-bold">ARIANNE JOY A. PURAZO-DUQUE</p>
            <p>State Auditor III</p>
            <p>Audit Team Leader, SKSU</p>
            <p class="mt-2">Madame:</p>

            <p class="mt-4">
                This is in relation to the following cash advance issued to <span class="underline">[NAME]</span>:
            </p>

            <table class="w-full mt-2 mb-4">
                <tr>
                    <td class="border border-gray-800 px-2">DV number:</td>
                    <td class="border border-gray-800 px-2">2024-001</td>
                    <td class="border border-gray-800 px-2">End of travel/implementation/payroll period:</td>
                    <td class="border border-gray-800 px-2">March 15, 2024</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 px-2">Amount:</td>
                    <td class="border border-gray-800 px-2">₱50,000</td>
                    <td class="border border-gray-800 px-2">Liquidation deadline:</td>
                    <td class="border border-gray-800 px-2">March 20, 2024</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 px-2">Date disbursed:</td>
                    <td class="border border-gray-800 px-2">March 1, 2024</td>
                    <td class="border border-gray-800 px-2">Purpose:</td>
                    <td class="border border-gray-800 px-2">Office Supplies Procurement</td>
                </tr>
            </table>

            <p>
                As of this writing, the cash advance has been outstanding for <span class="underline">xx</span> days from the end of the travel/implementation/payroll period.
            </p>

            <p class="mt-4">
                Management had already issued the following notices:
            </p>
            <ul class="list-decimal pl-6 mt-2">
                <li>Formal Management Reminder, FMR No. xxxx-xxxx dated [date], received [date]</li>
                <li>Formal Management Demand, FMD No. xxxx-xxxx dated [date], received [date]</li>
                <li>Show Cause Order, Office of the President Memo No. xxx, s. 20xx dated [date], received [date]</li>
            </ul>

            <p class="mt-4">
                Despite the above, the accountable officer has failed to comply. This constitutes wilful negligence and refusal to comply with legal obligations.
            </p>

            <p class="mt-4">
                In view of the foregoing, request is hereby made for the issuance of a FORMAL DEMAND as per Section 5 of CSC Memorandum Circular No. 23, s. 2019.
            </p>

            <p class="mt-4">
                We appreciate the transmittal of the requested document within three (3) working days from receipt of this endorsement.
            </p>

            <p class="mt-4">We look forward to your usual support.</p>

            <p class="mt-6 font-bold">[NAME]</p>
            <p>University President</p>
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
