<div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
    <x-sksu-header />
    <h1 class="text-xl font-bold pt-1 mt-2 text-center">Office of the President</h1>

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

        <div class=" text-xs text-gray-900 mt-2 ">
            <table class="w-full ">
                <tr>
                    <td class="border border-gray-800  px-2 ">DV number:</td>
                    <td class="border border-gray-800  px-2  ">2024-001</td>
                    <td class="border border-gray-800  px-2 ">End of travel/implementation/payroll period:</td>
                    <td class="border border-gray-800  px-2 ">March 15, 2024</td>
                </tr>
                <tr>
                    <td class="border border-gray-800  px-2 ">Amount:</td>
                    <td class="border border-gray-800  px-2 ">₱50,000</td>
                    <td class="border border-gray-800  px-2 ">Liquidation deadline:</td>
                    <td class="border border-gray-800  px-2 ">March 20, 2024</td>
                </tr>
                <tr>
                    <td class="border border-gray-800  px-2 ">Date disbursed:</td>
                    <td class="border border-gray-800  px-2 ">March 1, 2024</td>
                    <td class="border border-gray-800  px-2 ">Purpose:</td>
                    <td class="border border-gray-800  px-2 ">Office Supplies Procurement</td>
                </tr>
            </table>
        </div>
        <div>

        <p>
            Additionally, accounting documents reveal that you were issued a prior reminder through FMR No. xxxx-xxxx and a subsequent demand through FMD No. xxxx-xxxx, but no substantial compliance has been made by you in relation thereto. This already constitutes wilful negligence on your part with respect to a reasonable official order and an outright refusal by you to perform an obligation required by law.
        </p>

        <p class="mt-4">
            Now therefore, pursuant to Section 5 of the <em>Sanctions for Violations of Rules and Regulations Related to the Liquidation of Cash Advances</em> (hereinafter referred to as <strong>Sanctions</strong>), as adopted through BOR Resolution No. 56, s. 2024, you are <strong>DIRECTED TO LIQUIDATE</strong> your cash advance with the following alternatives:
        </p>

        <ol class="list-decimal pl-6 mt-2">
            <li>Liquidate the cash advance in full;</li>
            <li>Execute a Waiver for Deduction Against Personnel Pay for the outstanding balance of the cash advance; or</li>
            <li>Make partial liquidation of the cash advance and execute a Waiver for Deduction Against Personnel Pay for the remainder.</li>
        </ol>

        <p class="mt-4">
            Furthermore, pursuant to Section 7 of the same <strong>Sanctions</strong>, you are hereby <strong>ORDERED TO SHOW CAUSE</strong> in writing as to why you should not be cited for:
        </p>

        <ul class="list-disc pl-6 mt-2 text-xs">
            <li><strong>ADMINISTRATIVE</strong> offenses under Section 5 of CSC Memorandum Circular No. 23, s. 2019 in relation to Section 53, Rule 10 of the RRACCS; and</li>
            <li><strong>CRIMINAL</strong> offenses under the penal provisions of Paragraph 9.3.3 of COA Circular No. 97-002 in relation to Sections 89 and 128 of PD 1445, and under Article 218 of The Revised Penal Code.</li>
        </ul>

        <p class="mt-4">
            Your separate and concurrent obligations to liquidate and to show cause are due within three (3) working days from receipt of this notice. Legal action shall ensue upon your failure to comply.
        </p>

        <p class="mt-6 font-bold">[NAME]</p>
        <p>University President</p>
        </div>
    </div>
</div>

<button onclick="printDiv('printableDiv')" class="mt-4 px-4 py-2 bg-primary-500 text-white rounded">
    Print Document
</button>

<script>
    function printDiv(divName) {
        const printContents = document.getElementById(divName).innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
