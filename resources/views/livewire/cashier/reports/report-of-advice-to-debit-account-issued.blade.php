<div class="bg-white p-6 text-gray-900">
    <div class="text-right italic text-xs">Appendix 13</div>

    <div class="text-center mt-2">
        <h2 class="text-base font-semibold tracking-wide">REPORT OF ADVICE TO DEBIT ACCOUNT ISSUED</h2>
        <div class="text-sm mt-1">Period Covered: <span class="inline-block border-b border-gray-500 w-56 align-bottom"></span></div>
    </div>

    <div class="grid grid-cols-2 gap-6 mt-6 text-sm">
        <div class="space-y-1">
            <div>Entity Name : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
            <div>Fund Cluster : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
            <div>Bank Name/Account No. : <span class="inline-block border-b border-gray-500 w-64 align-bottom"></span></div>
        </div>
        <div class="space-y-1">
            <div class="flex justify-end">Report No. : <span class="inline-block border-b border-gray-500 w-40 ml-2 align-bottom"></span></div>
            <div class="flex justify-end">Sheet No. : <span class="inline-block border-b border-gray-500 w-40 ml-2 align-bottom"></span></div>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full border border-gray-800 text-xs">
            <thead>
            <tr>
                <th colspan="2" class="border border-gray-800 text-center py-2">ADA</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">JEV No.</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">ORS/BURS No.</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">Responsibility<br>Center Code</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">Payee</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">UACS Object<br>Code</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">Nature of Payment</th>
                <th rowspan="2" class="border border-gray-800 text-center px-2 py-2">Amount</th>
            </tr>
            <tr>
                <th class="border border-gray-800 text-center px-2 py-1">Date</th>
                <th class="border border-gray-800 text-center px-2 py-1">Serial No.</th>
            </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < 12; $i++)
                <tr class="h-8">
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800"></td>
                    <td class="border border-gray-800 text-right pr-2"></td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-center">
        <div class="text-xs tracking-wider">CERTIFICATION</div>
        <div class="mt-2 text-[11px] leading-5 text-center">
            I hereby certify on my official oath that the above is a true statement of all ADAs issued by me during<br>
            the period stated above for which ADA Nos. <span class="inline-block border-b border-gray-500 w-24 align-bottom"></span>
            to <span class="inline-block border-b border-gray-500 w-24 align-bottom"></span>, inclusive, were actually issued by me in the amounts shown thereon.
        </div>

        <div class="flex flex-col items-center gap-4 mt-8 text-[11px]">
            <div class="text-center flex flex-col items-center">
                <div class="border-b border-gray-500 w-full mx-auto"></div>
                <div class="mt-1 text-center px-8">Name and Signature of Disbursing Officer/Cashier</div>
                <div class="grid grid-cols-3 gap-2 mt-8">
                    <div class="col-span-2">
                        <div class="border-b border-gray-500 w-full"></div>
                        <div class="mt-1">Official Designation</div>
                    </div>
                    <div class="pl-8">
                        <div class="border-b border-gray-500 w-full"></div>
                        <div class="mt-1 px-8">Date</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
