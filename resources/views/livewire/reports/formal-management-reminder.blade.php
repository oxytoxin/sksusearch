<div>
    <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
         <x-sksu-header />


        <div class="mb-4 text-gray-800 my-6">
            <h1 class="text-xl font-bold   text-center ">
                Formal Management Reminder
            </h1>
            <p class="text-center">No. xxxx-xxxx</p>
        </div>

        <div class="border-b pb-2 border-black text-gray-800 text-xs">
            <div class="flex justify-start font-bold">
                <p class="label min-w-12">To:</p>
                <div class="">Kristine Mae Ampas</div>
            </div>
            <div class="flex justify-start font-bold">
                <p class="label min-w-12">Re:</p>
                <div class="">Reminder to liquidate cash advance</div>
            </div>
            <div class="flex justify-start font-bold">
                <p class="label min-w-12">Date:</p>
                <div class="">January 10, 2025</div>
            </div>
        </div>

        <div class="mt-4 text-xs text-gray-800 leading-relaxed">
            <p>
                Pursuant to Section 1 of the Sanctions for Violations of Rules and Regulations Related to the Liquidation of
                Cash Advances, as adopted through BOR Resolution No. 56, s. 2024, your attention is hereby drawn to the
                following cash advance that is now due for liquidation:
            </p>
        </div>

        <div class="text-xs text-gray-900 mt-2">
            <table class="w-full">
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
        </div>

        <div class="mt-4 text-xs text-gray-800 leading-relaxed">
            <p>Please be informed that cash advances, depending on the type, must be liquidated by the following deadlines:</p>
            <ul class="list-decimal pl-6 mt-2">
                <li>Salaries, wages, etc.: within five (5) days after each 15-day/month-end pay period<sup>1</sup></li>
                <li>Local travel: within thirty (30) days after return to permanent official station<sup>2</sup></li>
                <li>Foreign travel: within sixty (60) days after return to the Philippines<sup>3</sup></li>
                <li>Special activities: within twenty (20) days from accomplishment of the purpose<sup>4</sup></li>
            </ul>

            <p class="mt-4">
                Immediate liquidation of the aforementioned cash advance is therefore directed. If partial or full
                liquidation has already been made, please coordinate with the Accounting Office for validation and correction
                of records.
            </p>

            <p class="mt-6">For your guidance and immediate compliance.</p>
        </div>

        <div class="mt-6 text-xs text-gray-800 ">
            <p class="font-bold">[ACCOUNTANT’S SIGNATURE OVER PRINTED NAME]</p>
            <p>Accountant III</p>
        </div>

        <div class="mt-12 text-xs text-gray-800">
            <div class="border-b w-64 mb-2 border-gray-800"></div>
            <p><sup>1</sup> Section 5.1.1, COA Circular No. 97-002 dated February 10, 1997</p>
            <p><sup>2</sup> Section 5.1.3, Ibid.</p>
            <p><sup>3</sup> Ibid.</p>
            <p><sup>4</sup> Section 1, COA Circular No. 2012-004 dated November 28, 2012</p>
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
