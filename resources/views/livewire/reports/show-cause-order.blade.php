<div>
    <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
        <x-sksu-header />
        <h1 class="text-xl font-bold  pt-1 mt-2 text-center">
            Office of the President
        </h1>

        <div class="text-xs text-gray-800">

            <p class="mt-4 font-bold">Memorandum No. _____, s. 2024</p>

            <div class="mt-4">
                <div class="flex font-bold">
                    <span class="min-w-12">To:</span>
                    <span>[NAME]</span>
                </div>
                <div class="flex font-bold">
                    <span class="min-w-12">From:</span>
                    <span>JESUSA D. ORTUOSTE</span>
                </div>
                <div class="flex font-bold">
                    <span class="min-w-12">Re:</span>
                    <span>SHOW CAUSE ORDER</span>
                </div>
                <div class="flex font-bold">
                    <span class="min-w-12">Date:</span>
                    <span>OCTOBER 10, 2023</span>
                </div>
            </div>

            <p class="mt-4">
                Official records show that you have been granted the following cash advance:
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
                You were previously served with:
            </p>
            <ul class="list-disc pl-6 mt-2">
                <li>Formal Management Reminder (FMR No. xxxx-xxxx)</li>
                <li>Formal Management Demand (FMD No. xxxx-xxxx)</li>
            </ul>

            <p class="mt-4">
                Despite these, no substantial compliance has been made. This constitutes wilful negligence and refusal to fulfill a legal obligation.
            </p>

            <p class="mt-4">
                Pursuant to Section 5 of the Sanctions (BOR Res. No. 56, s. 2024), you are DIRECTED TO LIQUIDATE your cash advance via:
            </p>

            <ol class="list-decimal pl-6 mt-2">
                <li>Liquidate the cash advance in full</li>
                <li>Execute a Waiver for Deduction Against Personnel Pay</li>
                <li>Partial liquidation and execute a Waiver for the remainder</li>
            </ol>

            <p class="mt-4">
                In addition, as per Section 7, you are ORDERED TO SHOW CAUSE in writing why no ADMINISTRATIVE and CRIMINAL charges should be filed under:
            </p>

            <ul class="list-disc pl-6 mt-2 text-xs">
                <li>CSC Memo Circular No. 23, s. 2019, Sec. 5 | RRACCS Rule 10, Sec. 53</li>
                <li>COA Circular No. 97-002, par. 9.3.3 | PD No. 1445, Secs. 89 & 128</li>
                <li>Article 218, Revised Penal Code</li>
            </ul>

            <p class="mt-4">
                Your reply and liquidation must be submitted within three (3) working days from receipt. Failure to comply shall result in legal action.
            </p>

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
